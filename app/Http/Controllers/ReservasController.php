<?php

namespace App\Http\Controllers;

use App\Models\Reservas;
use Illuminate\Http\Request;

class ReservasController extends Controller
{
    // Método para obtener todas las reservas (GET)
    public function index()
    {
        $reservas = Reservas::with(['sala', 'juzgado', 'usuario'])->get();
        return response()->json(['message' => 'Reservas obtenidas con éxito', 'data' => $reservas]);
    }

    // Método para obtener una reserva específica por ID (GET)
    public function show($id)
    {
        $reserva = Reservas::with(['sala', 'juzgado', 'usuario'])->findOrFail($id);
        return response()->json(['message' => 'Reserva obtenida con éxito', 'data' => $reserva]);
    }

    // Método para crear una nueva reserva en la base de datos (POST)
    public function store(Request $request)
    {
        // Validación de los campos requeridos
        $request->validate([
            'id_sala' => 'required|exists:salas,id_sala',
            'id_juzgado' => 'required|exists:juzgados,id_juzgado',
            'id_usuario' => 'required|exists:users,id',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        // Creación de la reserva y retorno del objeto creado
        $reserva = Reservas::create($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Reserva creada con éxito', 'data' => $reserva], 201);
    }

    // Método para actualizar una reserva existente (PUT)
    public function update(Request $request, $id)
    {
        // Búsqueda de la reserva por ID
        $reserva = Reservas::findOrFail($id);

        // Validación de los campos que se pueden actualizar
        $request->validate([
            'id_sala' => 'sometimes|required|exists:salas,id_sala',
            'id_juzgado' => 'sometimes|required|exists:juzgados,id_juzgado',
            'id_usuario' => 'sometimes|required|exists:users,id',
            'descripcion' => 'nullable|string',
            'fecha' => 'sometimes|required|date',
            'hora_inicio' => 'sometimes|required|date_format:H:i',
            'hora_fin' => 'sometimes|required|date_format:H:i|after:hora_inicio',
            'observaciones' => 'nullable|string',
            'estado' => 'sometimes|required|in:pendiente,confirmada,cancelada',
        ]);

        // Actualización de la reserva
        $reserva->update($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Reserva actualizada con éxito', 'data' => $reserva]);
    }

    // Método para eliminar una reserva (DELETE)
    public function destroy($id)
    {
        // Búsqueda de la reserva por ID
        $reserva = Reservas::findOrFail($id);

        // Eliminación de la reserva
        $reserva->delete();

        // Mensaje de éxito
        return response()->json(['message' => 'Reserva eliminada con éxito']);
    }
}