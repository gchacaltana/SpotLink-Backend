<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class AuthTest extends TestCase
{
    /**
     * Prueba para autenticar un usuario
     */
    public function test_login(): void
    {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        $payload = [
            'email' => 'gchacaltanab@outlook.com',
            'password' => 'password'
        ];
        $response = $this->post('/api/login', $payload);
        $response->assertStatus(401);
    }
}
