<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //crea 1 empresa
        Empresa::factory(1)->create();
        //crea 10 trabajadores
        User::factory(10)->create();
        //crea 1 admin
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => "admin@admin.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'empresa_id' => 1,
            'role' => 'admin',
        ]);
        //prueba para ver si funciona el login
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => "prueba@gmail.com",
            'email_verified_at' => now(),
            'password' => "password",
            'remember_token' => Str::random(10),
            'empresa_id' => 1,
            'role' => 'admin',
        ]);


    }
}
