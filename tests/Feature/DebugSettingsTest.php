<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DebugSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug_settings_put(): void
    {
        $this->post('/manage/login', ['username' => 'Doman', 'password' => 'password']);

        $response = $this->put('/manage/settings', [
            'site_name' => 'YUNITAVERSE',
            'timezone' => 'Asia/Jakarta',
            'profile_name' => 'X',
            'accent' => 'amber',
            'text' => [
                'id' => ['enter_label' => 'Masuk ke dunia'],
                'en' => ['enter_label' => 'Step inside'],
            ],
        ]);

        fwrite(STDERR, "\n=== REDIRECT: " . $response->headers->get('Location') . "\n");
        fwrite(STDERR, "=== ERRORS: " . json_encode(session('errors')?->all()) . "\n");

        $rows = Setting::query()->whereIn('key', ['text.id.enter_label', 'text.en.enter_label'])->get();
        foreach ($rows as $row) {
            fwrite(STDERR, "=== ROW: {$row->key} = " . var_export($row->value, true) . "\n");
        }
        fwrite(STDERR, "=== COUNT: " . Setting::query()->count() . "\n");

        $this->assertTrue(true);
    }
}
