<?php

namespace App\Http\Controllers;

use App\Models\Juzgados;
use Illuminate\Http\Request;

class JuzgadosController extends Controller
{
    // Método para obtener todos los Juzgados (GET)
    public function index()
    {
        return Juzgados::all();
    }

    // Método para obtener un Juzgado específico por ID (GET)
    public function show($id_juzgado)
    {
        return Juzgados::findOrFail($id_juzgado);
    }

    // Método para crear un nuevo Juzgado en la base de datos (POST)
    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'nom_juzgado' => 'required|string|max:50',
            'id_sede' => 'required|exists:sedes,id_sede', // Validación para verificar si existe la sede
            
        ]);

        // Creación del Juzgado y retorno del objeto creado
        $juzgado = Juzgados::create($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Juzgado creado con éxito', 'data' => $juzgado], 201);
    }

    // Método para actualizar un Juzgado existente (PUT)
    public function update(Request $request, $id_juzgado)
    {
        // Búsqueda del Juzgado por ID
        $juzgado = Juzgados::findOrFail($id_juzgado);

        // Validación de los campos que se pueden actualizar
        $request->validate([
            'nom_juzgado' => 'required|string|max:50',
            'id_sede' => 'required|exists:sedes,id_sede', // Validación para verificar si existe la sede
            
        ]);

        // Actualización del Juzgado
        $juzgado->update($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Juzgado actualizado con éxito', 'data' => $juzgado]);
    }

    // Método para eliminar un Juzgado (DELETE)
    public function destroy($id_juzgado)
    {
        // Búsqueda de un Juzgado por ID
        $juzgado = Juzgados::findOrFail($id_juzgado);

        // Eliminación del Juzgado
        $juzgado->delete();

        // Mensaje de éxito
        return response()->json(['message' => 'Juzgado eliminado con éxito']);
    }
}
