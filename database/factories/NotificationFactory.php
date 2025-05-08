<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotificationsPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public static function createMultiples()
    {
        Notification::create([
            'user_id' => 11,
            'tipo' => 'ayuda',
            'message' => 'Has solicitado ayuda a Administración',
            'email' => 'usuario11@example.com',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'duda' => '¿Cómo cambio mi contraseña?',
            'read' => false,
        ]);

        Notification::create([
            'user_id' => 11,
            'tipo' => 'turno',
            'message' => 'Tu turno del jueves ha sido actualizado',
            'email' => 'usuario11@example.com',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'duda' => null,
            'read' => false,
        ]);

        Notification::create([
            'user_id' => 11,
            'tipo' => 'sistema',
            'message' => 'Nueva actualización del sistema disponible',
            'email' => 'usuario11@example.com',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'duda' => null,
            'read' => true,
        ]);

        Notification::create([
            'user_id' => 11,
            'tipo' => 'otras',
            'message' => 'Nueva prueba de notificacion',
            'email' => 'usuario11@example.com',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'duda' => null,
            'read' => true,
        ]);

        foreach (User::all() as $user) {
            UserNotificationsPreference::create([
                'user_id' => $user->id,
                'ayuda' => true,
                'turno' => true,
                'sistema' => true,
                'otras' => true,
            ]);
        }
    }
}
