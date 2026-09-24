<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Moment;
use App\Models\ModNote;
use App\Models\ModPlaylist;
use App\Models\ModSurprise;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class YunitaverseTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $yunita;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'Doman',
            'name' => 'Doman',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $this->yunita = User::create([
            'username' => 'Yunita',
            'name' => 'Yunita Dwi Alung',
            'role' => 'yunita',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_landing_shows_brand_and_person(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('YUNITAVERSE')
            ->assertSee('Yunita Dwi Alung')
            ->assertDontSee('manage/login')
            ->assertDontSee('Admin');
    }

    public function test_home_hub_shows_four_doors(): void
    {
        $this->get('/home')
            ->assertOk()
            ->assertSee('ARSIP DIA')
            ->assertSee('MOD')
            ->assertSee('SOUNDTRACK')
            ->assertSee('RUANGKU');
    }

    public function test_archive_index_links_all_sections(): void
    {
        Setting::set('text.id.archive_label_profile', 'Profil');
        Setting::set('text.id.archive_label_moments', 'Momen');
        Setting::set('text.id.archive_label_journey', 'Perjalanan');
        Setting::set('text.id.archive_label_achievements', 'Pencapaian');
        Setting::set('text.id.archive_label_activities', 'Aktivitas');
        Setting::set('text.id.archive_label_favorites', 'Favorit');

        $this->get('/archive')
            ->assertOk()
            ->assertSee('Profil')
            ->assertSee('Momen')
            ->assertSee('Perjalanan')
            ->assertSee('Pencapaian')
            ->assertSee('Aktivitas')
            ->assertSee('Favorit');
    }

    public function test_archive_pages_load_and_use_yunita_name(): void
    {
        Profile::create(['name' => 'Yunita Dwi Alung', 'bio' => 'Hi.', 'quote' => 'Enjoy the little things.']);

        $this->get('/archive/profile')->assertOk()->assertSee('Yunita Dwi Alung');
        $this->get('/archive/moments')->assertOk()->assertSee('Belum ada apa-apa di sini.');
        $this->get('/archive/journey')->assertOk();
        $this->get('/archive/achievements')->assertOk();
        $this->get('/archive/activities')->assertOk();
        $this->get('/archive/favorites')->assertOk();
    }

    public function test_moments_show_when_published(): void
    {
        $this->yunita->moments()->create([
            'title' => 'Sunday Afternoon',
            'caption' => 'One of those quiet afternoons.',
            'status' => 'published',
        ]);

        $this->get('/archive/moments')->assertOk()->assertSee('Sunday Afternoon');
    }

    public function test_draft_content_is_hidden_from_public(): void
    {
        $this->yunita->moments()->create(['title' => 'Secret Draft', 'status' => 'draft']);

        $this->get('/archive/moments')->assertOk()->assertDontSee('Secret Draft');
    }

    public function test_mod_pages_load(): void
    {
        $this->get('/mod')->assertOk()->assertSee('Bagaimana kabarmu hari ini?');
        $this->get('/mod/good')->assertOk();
        $this->get('/mod/normal')->assertOk();
        $this->get('/mod/sad')->assertOk()->assertSee('Ambil waktu mu.');
        $this->get('/mod/unknown-mood')->assertNotFound();
    }

    public function test_mod_content_shows_per_mood(): void
    {
        ModNote::create(['content' => 'Pelan-pelan saja.', 'mood' => 'sad', 'created_by' => $this->yunita->id, 'status' => 'published']);
        ModPlaylist::create([
            'title' => 'Soft & Calm',
            'mood' => 'sad',
            'spotify_url' => 'https://open.spotify.com/playlist/37i9dQZF1DWXe9gFGH69X7',
            'created_by' => $this->yunita->id,
            'status' => 'published',
        ]);

        $this->get('/mod/sad/notes')->assertOk()->assertSee('Pelan-pelan saja.');
        $this->get('/mod/sad/music')->assertOk()->assertSee('Soft & Calm')
            ->assertSee('open.spotify.com/embed/playlist/37i9dQZF1DWXe9gFGH69X7', false);
    }

    public function test_surprise_returns_random_content(): void
    {
        ModSurprise::create(['type' => 'quote', 'content' => 'Enjoy the little things.', 'mood' => 'all', 'created_by' => $this->yunita->id, 'status' => 'published']);

        $data = $this->getJson('/mod/surprise?mood=sad')->assertOk()->json();
        $this->assertSame('Enjoy the little things.', $data['content']);
    }

    public function test_soundtrack_page_loads(): void
    {
        $this->get('/soundtrack')->assertOk();
    }

    public function test_yunita_can_login_and_see_my_space(): void
    {
        $this->post('/login', ['username' => 'Yunita', 'password' => 'password'])
            ->assertRedirect(route('my-space.index'));

        $this->actingAs($this->yunita)->get('/my-space')
            ->assertOk()
            ->assertSee('Selamat datang kembali, Yunita Dwi Alung.');
    }

    public function test_yunita_can_create_and_delete_her_own_moment(): void
    {
        $this->actingAs($this->yunita);

        $this->post('/my-space/moments', [
            'title' => 'My Little Moment',
            'caption' => 'A good day.',
            'status' => 'published',
        ])->assertRedirect(route('my-space.type', 'moments'));

        $moment = Moment::where('title', 'My Little Moment')->firstOrFail();
        $this->assertSame($this->yunita->id, $moment->created_by);

        $this->delete("/my-space/moments/{$moment->id}")
            ->assertRedirect(route('my-space.type', 'moments'));

        $this->assertDatabaseMissing('moments', ['id' => $moment->id]);
    }

    public function test_yunita_cannot_edit_anothers_content_via_url(): void
    {
        $foreign = Moment::create([
            'title' => 'Not Mine',
            'created_by' => $this->admin->id,
            'status' => 'published',
        ]);

        $this->actingAs($this->yunita)
            ->get("/my-space/moments/{$foreign->id}/edit")
            ->assertNotFound();

        $this->actingAs($this->yunita)
            ->put("/my-space/moments/{$foreign->id}", ['title' => 'Hacked', 'status' => 'published'])
            ->assertNotFound();

        $this->assertDatabaseHas('moments', ['id' => $foreign->id, 'title' => 'Not Mine']);
    }

    public function test_my_space_requires_auth(): void
    {
        $this->get('/my-space')->assertRedirect('/login');
    }

    public function test_manage_login_rejects_yunita_role(): void
    {
        $this->post('/manage/login', ['username' => 'Yunita', 'password' => 'password'])
            ->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_admin_can_login_and_use_dashboard(): void
    {
        $this->post('/manage/login', ['username' => 'Doman', 'password' => 'password'])
            ->assertRedirect(route('manage.dashboard'));

        $this->actingAs($this->admin)->get('/manage/dashboard')
            ->assertOk()
            ->assertSee('Dasbor');
    }

    public function test_manage_routes_are_protected(): void
    {
        foreach ([
            '/manage/dashboard',
            '/manage/profile',
            '/manage/users',
            '/manage/settings',
            '/manage/content/moments',
        ] as $path) {
            $this->get($path)->assertRedirect(route('manage.login'));
        }
    }

    public function test_admin_full_crud_on_content(): void
    {
        $this->actingAs($this->admin);

        $this->post('/manage/content/achievements', [
            'title' => 'Finished Something Important',
            'caption' => 'You did it.',
            'status' => 'published',
        ])->assertRedirect(route('manage.content.index', 'achievements'));

        $achievement = Achievement::where('title', 'Finished Something Important')->firstOrFail();
        $this->assertSame($this->admin->id, $achievement->created_by);

        $this->put("/manage/content/achievements/{$achievement->id}", [
            'title' => 'Finished Something Important',
            'caption' => 'You really did it.',
            'status' => 'published',
        ])->assertRedirect(route('manage.content.index', 'achievements'));

        $this->post("/manage/content/achievements/{$achievement->id}/toggle")
            ->assertRedirect();

        $this->assertDatabaseHas('achievements', ['id' => $achievement->id, 'status' => 'draft']);

        $this->delete("/manage/content/achievements/{$achievement->id}")
            ->assertRedirect(route('manage.content.index', 'achievements'));

        $this->assertDatabaseMissing('achievements', ['id' => $achievement->id]);
    }

    public function test_admin_content_rejects_bad_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post('/manage/content/mod-photos', [
                'image' => UploadedFile::fake()->create('virus.exe', 100),
                'mood' => 'all',
                'status' => 'published',
            ])
            ->assertSessionHasErrors('image');
    }

    public function test_admin_can_upload_mod_photo(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post('/manage/content/mod-photos', [
                'image' => UploadedFile::fake()->image('photo.jpg', 100, 100),
                'title' => 'Calm Sky',
                'mood' => 'sad',
                'status' => 'published',
            ])
            ->assertRedirect(route('manage.content.index', 'mod-photos'));

        $this->assertDatabaseHas('mod_photos', ['title' => 'Calm Sky', 'mood' => 'sad']);
    }

    public function test_admin_can_update_settings(): void
    {
        $this->actingAs($this->admin)
            ->put('/manage/settings', [
                'site_name' => 'YUNITAVERSE',
                'site_tagline' => 'a little universe of her world',
                'timezone' => 'Asia/Jakarta',
                'profile_name' => 'Yunita Dwi Alung',
                'accent' => 'amber',
                'text' => [
                    'id' => [
                        'enter_label' => 'Masuk ke dunia',
                        'mod_question' => 'Apa kabar hari ini?',
                    ],
                    'en' => [
                        'enter_label' => 'Step inside',
                        'mod_question' => 'How are you feeling today?',
                    ],
                ],
            ])
            ->assertRedirect(route('manage.settings.edit'));

        $this->assertSame('a little universe of her world', Setting::get('site_tagline'));
        $this->assertSame('amber', Setting::get('accent'));
        $this->assertSame('Masuk ke dunia', Setting::get('text.id.enter_label'));
        $this->assertSame('How are you feeling today?', Setting::get('text.en.mod_question'));
    }

    public function test_public_pages_render_edited_text_from_settings(): void
    {
        Setting::set('text.id.enter_label', 'Masuk ke dunia');
        Setting::set('text.id.mod_question', 'Apa kabar hari ini?');

        $this->get('/')->assertOk()->assertSee('Masuk ke dunia');
        $this->get('/mod')->assertOk()->assertSee('Apa kabar hari ini?');
    }

    public function test_admin_can_manage_users(): void
    {
        $this->actingAs($this->admin)
            ->post('/manage/users', [
                'username' => 'NewPerson',
                'name' => 'New Person',
                'role' => 'yunita',
                'password' => 'secret123',
            ])
            ->assertRedirect(route('manage.users.index'));

        $this->assertDatabaseHas('users', ['username' => 'NewPerson', 'role' => 'yunita']);
    }

    public function test_seeder_creates_expected_defaults(): void
    {
        $this->artisan('db:seed')->assertSuccessful();

        $this->assertDatabaseHas('users', ['username' => 'Doman', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['username' => 'Yunita', 'role' => 'yunita']);
        $this->assertDatabaseHas('profiles', ['name' => 'Yunita Dwi Alung']);
        $this->assertDatabaseHas('settings', ['key' => 'site_name']);
    }

    /*
    |------------------------------------------------------------------
    | i18n
    |------------------------------------------------------------------
    */

    public function test_default_locale_is_indonesian(): void
    {
        $this->get('/mod')->assertOk()->assertSee('Bagaimana kabarmu hari ini?');
        $this->get('/')->assertOk()->assertSee('lang="id"', false);
    }

    public function test_switching_to_english_persists_across_pages(): void
    {
        $this->get('/mod?lang=en')->assertOk();

        // Same session, different page: English must stay active.
        $this->get('/mod')->assertOk()->assertSee('How are you today?');
        $this->get('/soundtrack')->assertOk()->assertSee('lang="en"', false);
    }

    public function test_switching_back_to_indonesian_persists(): void
    {
        $this->get('/mod?lang=en')->assertOk();
        $this->get('/mod?lang=id')->assertOk();

        $this->get('/mod')->assertOk()->assertSee('Bagaimana kabarmu hari ini?');
    }

    public function test_unknown_locale_is_rejected(): void
    {
        $this->get('/mod?lang=fr')->assertOk();

        $this->get('/mod')->assertOk()->assertSee('Bagaimana kabarmu hari ini?');
    }

    public function test_english_admin_copy_renders(): void
    {
        $this->post('/manage/login', ['username' => 'Doman', 'password' => 'password']);

        $this->get('/manage/dashboard?lang=en')
            ->assertOk()
            ->assertSee('Everything in the universe, at a glance.');

        $this->get('/manage/dashboard')
            ->assertOk()
            ->assertSee('Semua yang ada di alam semesta ini, dalam satu pandangan.');
    }
}
