<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DebugI18nTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug(): void
    {
        $admin = User::create([
            'username' => 'Doman',
            'name' => 'Doman',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $payload = [
            'site_name' => 'YUNITAVERSE',
            'timezone' => 'Asia/Jakarta',
            'profile_name' => 'Yunita Dwi Alung',
            'accent' => 'amber',
            'text' => [
                'id' => ['enter_label' => 'Masuk ke dunia'],
                'en' => ['enter_label' => 'Step inside'],
            ],
        ];

        $this->actingAs($admin)->put('/manage/settings', $payload);

        $req = request();

        $rules = [
            'site_name' => ['required', 'string', 'max:255'],
            'timezone' => ['required', 'string', 'max:100'],
            'profile_name' => ['required', 'string', 'max:255'],
            'accent' => ['required', 'in:moon,rose,amber,violet,teal'],
            'text.id.enter_label' => ['nullable', 'string', 'max:1000'],
            'text.en.enter_label' => ['nullable', 'string', 'max:1000'],
        ];

        $validated = $req->validate($rules);

        fwrite(STDERR, "\n=== HTTP validated keys: " . json_encode(array_keys($validated)) . "\n");
        fwrite(STDERR, "=== HTTP validated text: " . json_encode($validated['text'] ?? null) . "\n");

        $this->assertTrue(true);
    }
}
