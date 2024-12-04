<?php

namespace App\Http\Controllers;

use App\Models\Salas;
use Illuminate\Http\Request;

class SalasController extends Controller
{
    // Método para obtener todas las salas (GET)
    public function index()
    {
        return Salas::all();
    }

    // Método para obtener una sala específica por ID (GET)
    public function show($id_sala)
    {
        return Salas::findOrFail($id_sala);
    }

    // Método para crear una nueva sala en la base de datos (POST)
    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'nom_sala' => 'required|string|max:50',
            'capacidad' => 'required|integer',
            'id_sede' => 'required|exists:sedes,id_sede', // Validación para verificar si existe la sede
            
        ]);

        // Creación de la sala y retorno del objeto creado
        $sala = Salas::create($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Sala creada con éxito', 'data' => $sala], 201);
    }

    // Método para actualizar una sala existente (PUT)
    public function update(Request $request, $id_sala)
    {
        // Búsqueda de la sala por ID
        $sala = Salas::findOrFail($id_sala);

        // Validación de los campos que se pueden actualizar
        $request->validate([
            'nom_sala' => 'required|string|max:50',
            'capacidad' => 'required|integer',
            'id_sede' => 'required|exists:sedes,id_sede', // Validación para verificar si existe la sede
            
        ]);

        // Actualización de la sala
        $sala->update($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Sala actualizada con éxito', 'data' => $sala]);
    }

    // Método para eliminar una sala (DELETE)
    public function destroy($id_sala)
    {
        // Búsqueda de la sala por ID
        $sala = Salas::findOrFail($id_sala);

        // Eliminación de la sala
        $sala->delete();

        // Mensaje de éxito
        return response()->json(['message' => 'Sala eliminada con éxito']);
    }
}
