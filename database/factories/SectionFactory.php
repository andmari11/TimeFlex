<?php

namespace Database\Factories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle(),
            'min_hours' => 38,
            'max_hours' => 40,
            'min_shifts' => 1,
            'max_shifts' => 10000,
            'company_id' => 1,
        ];
    }
    public static function createMultiples():void
    {
        //crea 3 secciones, admin y sin seccion
        Section::factory(1)->create([
            'name' => "Administradores",
            'min_hours' => 38,
            'max_hours' => 40,
            'min_shifts' => 1,
            'max_shifts' => 10000,
            "company_id" => 1,
            'default' => true
        ]);
        Section::factory(1)->create([
            'id' => 0,
            'name' => 'Sin sección',
            'min_hours' => 38,
            'max_hours' => 40,
            'min_shifts' => 1,
            'max_shifts' => 10000,
            'company_id' => 1,
            'default' => true
        ]);
        Section::factory(1)->create([
            'name' => "Anestesistas",
            'min_hours' => 38,
            'max_hours' => 40,
            'min_shifts' => 5,
            'max_shifts' => 31,
            "company_id" => 1,
            'default' => false
        ]);
        Section::factory(1)->create([
            'name' => "Enfermeros",
            'min_hours' => 38,
            'max_hours' => 40,
            'min_shifts' => 5,
            'max_shifts' => 31,
            "company_id" => 1,
            'default' => false
        ]);
        Section::factory(1)->create([
            'name' => "Médicos de consulta",
            'min_hours' => 38,
            'max_hours' => 40,
            'min_shifts' => 5,
            'max_shifts' => 31,
            "company_id" => 1,
            'default' => false
        ]);
        Section::factory(1)->create([
            'name' => "Cirujanos",
            'min_hours' => 38,
            'max_hours' => 40,
            'min_shifts' => 5,
            'max_shifts' => 31,
            "company_id" => 1,
            'default' => false
        ]);

    }
}
