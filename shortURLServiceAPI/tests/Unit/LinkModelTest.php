<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Link;


class LinkModelTest extends TestCase
{
    /**
     * Prueba Unitaria para crear un link
     */
    public function test_create(): void
    {
        $payload = [
            'url' => 'http://www.example.com',
            'token' => 'abc123',
            'user_id' => 1
        ];

        $link = new Link($payload);
        $this->assertInstanceOf(Link::class, $link);
        $this->assertEquals($payload['url'], $link->url);
        $this->assertEquals($payload['token'], $link->token);
        $this->assertEquals($payload['user_id'], $link->user_id);
        $this->assertCount(3, $link->getAttributes());
    }

    /**
     * Prueba Unitaria para modificar un link
     */
    public function test_update(): void
    {
        $payload = [
            'url' => 'http://www.example.com',
            'token' => 'abc123',
            'user_id' => 1
        ];

        $link = new Link($payload);
        $newToken = "def456";
        $updated_at = $link->updated_at;
        $link->token = $newToken;
        $this->assertInstanceOf(Link::class, $link);
        $this->assertEquals($updated_at, $link->updated_at);
    }
}
