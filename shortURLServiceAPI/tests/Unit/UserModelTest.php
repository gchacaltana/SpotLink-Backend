<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserModelTest extends TestCase
{
    /**
     * Prueba Unitaria para crear un usuario
     */
    public function test_create(): void
    {
        $payload = [
            'name' => 'Gonzalo Chacaltana',
            'email' => 'gchacaltanab@outlook.com'
        ];

        $user = new User($payload);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($payload['name'], $user->name);
        $this->assertEquals($payload['email'], $user->email);
        $this->assertCount(2, $user->getAttributes());
    }

    /**
     * Prueba Unitaria para modificar un link
     */
    public function test_update(): void
    {
        $payload = [
            'name' => 'Gonzalo Chacaltana'
        ];
        $user = new User($payload);
        $user->name = "Gonzalo Chacaltana B";
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("Gonzalo Chacaltana B", $user->name);
        $this->assertCount(1, $user->getAttributes());
    }
}
