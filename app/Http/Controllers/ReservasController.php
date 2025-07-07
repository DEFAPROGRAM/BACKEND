<?php

namespace App\Http\Controllers;

use App\Models\Reservas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservasController extends Controller
{
    // Método para obtener todas las reservas (GET)
    public function index(Request $request)
    {
        $user = $request->user();
        \Log::info('ReservasController - Usuario autenticado:', [
            'id' => $user ? $user->id : 'null',
            'rol' => $user ? $user->rol : 'null',
            'email' => $user ? $user->email : 'null'
        ]);
        
        if ($user->rol === 'usuario') {
            // Usuario normal: solo ve sus propias reservas
            $reservas = Reservas::with(['sala', 'juzgado', 'usuario'])
                ->where('id_usuario', $user->id)
                ->get();
            \Log::info('ReservasController - Usuario normal, reservas encontradas:', ['count' => $reservas->count()]);
        } else {
            // Admin: ve todas las reservas
            $reservas = Reservas::with(['sala', 'juzgado', 'usuario'])->get();
            \Log::info('ReservasController - Admin, todas las reservas:', ['count' => $reservas->count()]);
        }
        
        return response()->json(['message' => 'Reservas obtenidas con éxito', 'data' => $reservas]);
    }

    // Método para obtener una reserva específica por ID (GET)
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $reserva = Reservas::with(['sala', 'juzgado', 'usuario'])->findOrFail($id);
        
        // Verificar permisos: usuario solo puede ver sus propias reservas
        if ($user->rol === 'usuario' && $reserva->id_usuario !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        return response()->json(['message' => 'Reserva obtenida con éxito', 'data' => $reserva]);
    }

    // Método para crear una nueva reserva en la base de datos (POST)
    public function store(Request $request)
    {
        $user = $request->user();
        
        // Validación de los campos requeridos
        $request->validate([
            'id_sala' => 'required|exists:salas,id_sala',
            'id_juzgado' => 'required|exists:juzgados,id_juzgado',
            'id_usuario' => 'required|exists:users,id',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date_format:d-m-Y',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        // Verificar permisos: usuario solo puede crear reservas para sí mismo
        if ($user->rol === 'usuario' && $request->id_usuario != $user->id) {
            return response()->json(['message' => 'No autorizado para crear reservas para otros usuarios'], 403);
        }

        // Convertir la fecha al formato Y-m-d para almacenar en la base de datos
        $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha)->format('Y-m-d');
        
        // Crear la reserva con la fecha convertida
        $reserva = Reservas::create(array_merge(
            $request->except('fecha'),
            ['fecha' => $fecha]
        ));

        // Mensaje de éxito
        return response()->json(['message' => 'Reserva creada con éxito', 'data' => $reserva], 201);
    }

    // Método para actualizar una reserva existente (PUT)
    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        // Búsqueda de la reserva por ID
        $reserva = Reservas::findOrFail($id);
        
        // Verificar permisos: usuario solo puede actualizar sus propias reservas
        if ($user->rol === 'usuario' && $reserva->id_usuario !== $user->id) {
            return response()->json(['message' => 'No autorizado para actualizar esta reserva'], 403);
        }

        // Validación de los campos que se pueden actualizar
        $request->validate([
            'id_sala' => 'sometimes|required|exists:salas,id_sala',
            'id_juzgado' => 'sometimes|required|exists:juzgados,id_juzgado',
            'id_usuario' => 'sometimes|required|exists:users,id',
            'descripcion' => 'nullable|string',
            'fecha' => 'sometimes|required|date_format:d-m-Y',
            'hora_inicio' => 'sometimes|required|date_format:H:i',
            'hora_fin' => 'sometimes|required|date_format:H:i|after:hora_inicio',
            'observaciones' => 'nullable|string',
            'estado' => 'sometimes|required|in:pendiente,confirmada,cancelada',
        ]);

        // Verificar permisos para cambio de usuario: usuario solo puede asignar a sí mismo
        if ($user->rol === 'usuario' && $request->has('id_usuario') && $request->id_usuario != $user->id) {
            return response()->json(['message' => 'No autorizado para cambiar el usuario de la reserva'], 403);
        }

        // Si se proporciona una nueva fecha, convertirla al formato Y-m-d
        if ($request->has('fecha')) {
            $fecha = Carbon::createFromFormat('d-m-Y', $request->fecha)->format('Y-m-d');
            $request->merge(['fecha' => $fecha]);
        }

        // Actualización de la reserva
        $reserva->update($request->all());

        // Mensaje de éxito
        return response()->json(['message' => 'Reserva actualizada con éxito', 'data' => $reserva]);
    }

    // Método para eliminar una reserva (DELETE)
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        // Búsqueda de la reserva por ID
        $reserva = Reservas::findOrFail($id);
        
        // Verificar permisos: usuario solo puede eliminar sus propias reservas
        if ($user->rol === 'usuario' && $reserva->id_usuario !== $user->id) {
            return response()->json(['message' => 'No autorizado para eliminar esta reserva'], 403);
        }

        // Eliminación de la reserva
        $reserva->delete();

        // Mensaje de éxito
        return response()->json(['message' => 'Reserva eliminada con éxito']);
    }
}

