<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function download($id)
    {
        $file = File::findOrFail($id);
        return Response::make($file->data, 200, [
            'Content-Type' => $file->mime,
            'Content-Disposition' => 'attachment; filename="' . $file->name . '"',
        ]);
    }

    public function show($id)
    {
        $file = File::findOrFail($id);
        return Response::make($file->data, 200, [
            'Content-Type' => $file->mime,
        ]);
    }
}



