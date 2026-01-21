<?php

namespace App\Http\Controllers;

use App\Archivo;
use Illuminate\Support\Facades\Storage;
use Exception;

class ArchivoController extends Controller
{
    public function destroy($id)
    {
        try {
            $archivo = Archivo::findOrFail($id);

            // borrar archivo físico
            if ($archivo->url_file && Storage::disk('public')->exists($archivo->url_file)) {
                Storage::disk('public')->delete($archivo->url_file);
            }

            // borrar registro
            $archivo->delete();

            return redirect()->back()->with('success', 'Archivo eliminado correctamente');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar archivo');
        }
    }
}