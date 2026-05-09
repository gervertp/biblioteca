<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_iniciar_sesion(): void
    {
        $user = User::factory()->create([
            'email'    => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user'  => ['id', 'name', 'email'],
                'token',
            ]);
    }

    public function test_login_falla_con_contrasena_incorrecta(): void
    {
        User::factory()->create(['email' => 'admin@test.com']);

        $response = $this->postJson('/api/login', [
            'email'    => 'admin@test.com',
            'password' => 'contrasena_incorrecta',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Credenciales incorrectas.']);
    }

    public function test_login_falla_sin_email(): void
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_usuario_puede_cerrar_sesion(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Sesión cerrada correctamente.']);
    }
}
