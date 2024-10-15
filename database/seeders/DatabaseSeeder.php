<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Section;
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
        Company::factory(1)->create();
        //crea 3 secciones
        $sections = Section::factory(3)->create();
        //crea 10 trabajadores
        User::factory(10)->create();

        //crea 1 admin
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => "admin@admin.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_id' => 1,
            'role' => 'admin',
            'section_id' => $sections->random()->id
        ]);



    }
}
