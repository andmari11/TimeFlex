<?php
namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function export()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Email', 'Rol', 'Peso', 'Seccion', 'Horas mínimas', 'Horas maximas', 'Turnos minimos', 'Turnos maximos']);

            $data = DB::select("SELECT users.id, users.email, users.role, users.weight,
                                sections.name, sections.min_hours, sections.max_hours,
                                sections.min_shifts, sections.max_shifts
                                FROM users
                                JOIN sections ON users.section_id = sections.id");

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->id, $row->email, $row->role, $row->weight,
                    $row->name, $row->min_hours, $row->max_hours,
                    $row->min_shifts, $row->max_shifts
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="usuarios_secciones.csv"');

        return $response;
    }
}
