<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Schedule;
use App\Models\Shift;
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
        Company::factory(config('const.seeder.companies'))->create();
        //crea 3 secciones, admin y sin seccion
        Section::factory(1)->create([
            'name'  => "Administradores",
            "company_id"=> 1,
            'default'=>true
        ]);
        Section::factory(1)->create([
            'id' => 0,
            'name' => 'Sin sección',
            'company_id' => 1,
            'default'=>true
        ]);
        Section::factory(config('const.seeder.sections'))->create();
        //crea 10 trabajadores
        User::factory(config('const.seeder.employees'))->create();

        //crea 1 admin
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => "admin@admin.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_id' => 1,
            'role' => 'admin',
            'section_id' => 1
        ]);

        //crea 1 user
        User::factory(1)->create([
            'name' => fake()->name(),
            'email' => "user@user.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_id' => 1,
            'role' => 'employee',
            'section_id' => 0
        ]);
        Schedule::factory(2)->create();
        for ($i = 0; $i < 5; $i++) {
            Shift::factory()->create([
                'begin' => now()->addDays($i)->setTime(9, 0),
                'end' => now()->addDays($i)->setTime(15, 0),
            ]);

            Shift::factory()->create([
                'begin' => now()->addDays($i)->setTime(15, 0),
                'end' => now()->addDays($i)->setTime(21, 0),
            ]);

            Shift::factory()->create([
                'begin' => now()->addDays($i)->setTime(21, 0),
                'end' => now()->addDays($i + 1)->setTime(4, 0),
            ]);
        }


    }
}
