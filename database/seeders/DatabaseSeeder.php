<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'name' => 'Usuário Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'is_admin' => true, 
        ]);

        User::create([
            'name' => 'Usuário Padrão',
            'email' => 'user@teste.com',
            'password' => Hash::make('12345678'),
            'is_admin' => false, 
        ]);
    }
}
