<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkTest extends TestCase
{
    /**
     * Pryeba para crear un link
     */
    public function test_post(): void
    {
        $payload = [
            'url' => 'https://infoq.com'
        ];
        $response = $this->post('/api/v1/links', $payload);
        $response->assertStatus(401);
    }

    /**
     * Prueba para obtener un link
     */
    public function test_get(): void
    {
        $response = $this->get('/api/v1/links');
        $response->assertStatus(401);
    }
}
