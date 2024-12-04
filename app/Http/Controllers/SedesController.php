<?php

namespace App\Http\Controllers;

use App\Models\Sedes;
use Illuminate\Http\Request;

class SedesController extends Controller
{
    // Método para obtener todas las sedes (GET)
    public function index()
    {
        return Sedes::all();
    }

    // Método para obtener una sede específica por ID (GET)
    public function show($id)
    {
        return Sedes::findOrFail($id);
    }

    // Método para crear una nueva sede en la base de datos (POST)
    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'nom_sede' => 'required|string|max:50',
            'direccion' => 'required|string|max:100',
            'municipio' => 'required|string|max:50',
        ]);

        // Creación de la sede y retorno del objeto creado
        $sede = Sedes::create($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Sede creada con éxito', 'data' => $sede], 201);
    }

    // Método para actualizar una sede existente (PUT)
    public function update(Request $request, $id)
    {
        // Búsqueda de la sede por ID
        $sede = Sedes::findOrFail($id);

        // Validación de los campos que se pueden actualizar
        $request->validate([
            'nom_sede' => 'sometimes|required|string|max:50',
            'direccion' => 'sometimes|required|string|max:100',
            'municipio' => 'sometimes|required|string|max:50',
        ]);

        // Actualización de la sede
        $sede->update($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Sede actualizada con éxito', 'data' => $sede]);
    }

    // Método para eliminar una sede (DELETE)
    public function destroy($id)
    {
        // Búsqueda de la sede por ID
        $sede = Sedes::findOrFail($id);

        // Eliminación de la sede
        $sede->delete();

        // Mensaje de éxito
        return response()->json(['message' => 'Sede eliminada con éxito']);
    }
}