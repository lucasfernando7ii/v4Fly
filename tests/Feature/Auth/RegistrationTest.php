<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase; // Importante!
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase; // <-- Use este trait!

    /**
     * Testa se um usuário pode se registrar com dados válidos.
     * O nome do método DEVE começar com 'test'.
     *
     * @return void
     */
    public function test_a_user_can_register_with_valid_data(): void
    {
        // 1. PREPARAÇÃO (Arrange)
        // Definimos os dados que vamos enviar para a API.
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // 2. AÇÃO (Act)
        // Simulamos uma requisição POST para a nossa rota de registro.
        // O método 'postJson' já envia os dados como JSON e com os headers corretos.
        $response = $this->postJson('/api/register', $userData);

        // 3. VERIFICAÇÕES (Assert)
        // Verificamos se a resposta do servidor foi a esperada.

        // A resposta teve o status 201 Created?
        $response->assertStatus(201);

        // A resposta contém a mensagem de sucesso?
        $response->assertJson(['message' => 'Usuário registrado com sucesso!']);

        // O usuário realmente foi criado no banco de dados?
        // O método 'assertDatabaseHas' verifica na tabela 'users'
        // se existe um registro com o email que enviamos.
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
