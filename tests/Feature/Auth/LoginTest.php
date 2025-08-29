<?php

namespace Tests\Feature\Auth;

use App\Models\User; // Precisamos do Model User
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash; // Precisamos do Hash para criar a senha
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se um usuário existente pode fazer login com credenciais válidas.
     */
    public function test_an_existing_user_can_login_with_valid_credentials(): void
    {
        // 1. PREPARAÇÃO (Arrange)
        // Primeiro, criamos um usuário no banco de dados de teste.
        // Usamos o factory do User para facilitar.
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password123'), // Senha precisa ser hasheada
        ]);

        // Dados que vamos enviar na requisição de login
        $loginData = [
            'email' => 'login@example.com',
            'password' => 'password123', // Senha em texto plano
        ];

        // 2. AÇÃO (Act)
        // Simulamos a requisição POST para a rota de login.
        $response = $this->postJson('/api/login', $loginData);

        // 3. VERIFICAÇÕES (Assert)
        // A resposta teve o status 200 OK?
        $response->assertStatus(200);

        // A resposta contém a estrutura esperada (access_token, user, etc)?
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => [
                'id',
                'name',
                'email',
            ],
        ]);
    }
}
