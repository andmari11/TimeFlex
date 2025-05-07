<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Section;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role'=> 'employee',
            'company_id' => 1,
            'section_id' => random_int(2,config('const.seeder.sections')+1)
            //'role' => fake()->randomElement(['admin', 'user']),

            //'empresa_id' => Empresa::inRandomOrder()->value('id') ?? Empresa::factory()->create()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public static function createMultiples()
    {
        User::factory(1)->create([
            'name' => 'Usuario',
            'email' => "user@user.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_id' => 1,
            'role' => 'employee',
            'section_id' => 0
        ]);
        User::factory()->create([
            'name' => 'Administrador',
            'email' => "admin@admin.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'company_id' => 1,
            'role' => 'admin',
            'weight' => 5,
            'section_id' => 1
        ]);

        $secciones = [
            2 => [
                'Juan Pérez', 'Marta Ruiz', 'Sergio Navarro', 'Laura Ortega', 'Pedro Molina',
                'Sandra Ramos', 'David Ibáñez', 'Raquel Vidal', 'Antonio Peña', 'Nuria Lozano'
            ],
            3 => [
                'Jorge Cano', 'Beatriz Herrera', 'Rubén Morales', 'Cristina León', 'Iván Salas',
                'Patricia Gil', 'Óscar Méndez', 'Eva Castro', 'Andrés Ríos', 'Clara Domínguez'
            ],
            4 => [
                'Manuel Serrano', 'Elena Varela', 'Adrián Romero', 'Rosa Campos', 'Diego Pascual',
                'Lucía Cordero', 'Alberto Pastor', 'Carla Ferrer', 'Tomás Nieto', 'Irene Benítez'
            ],
            5 => [
                'Álvaro Aguado', 'Celia Marín', 'Hugo Esteban', 'Aitana Valle', 'Enrique Cortés',
                'Sofía Blanco', 'Pablo Lobo', 'Lidia Bermejo', 'Daniel Soler', 'Noelia Saez'
            ]
        ];

        foreach ($secciones as $sectionId => $nombres) {
            foreach ($nombres as $index => $nombre) {

                User::factory()->create([
                    'name' => $nombre,
                    'email' => 'usuario' . $sectionId .$index . '@hospital.es',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'company_id' => 1,
                    'role' => 'employee',
                    'section_id' => $sectionId
                ]);
            }
        }
    }
}
