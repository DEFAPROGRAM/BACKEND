<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['message' => 'Usuarios obtenidos con éxito', 'data' => $users]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['message' => 'Usuario obtenido con éxito', 'data' => $user]);
    }

    public function store(Request $request)
    {
        Log::info('Datos recibidos:', $request->all());

        $request->validate([
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'cargo' => 'required|string|max:50',
            'id_sede' => 'required|exists:sedes,id_sede',
            'id_juzgado' => 'required|exists:juzgados,id_juzgado',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'rol' => 'required|string|in:admin,usuario',
        ]);

        try {
            $user = User::create([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'cargo' => $request->cargo,
                'id_sede' => $request->id_sede,
                'id_juzgado' => $request->id_juzgado,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
            ]);

            Log::info('Usuario creado:', $user->toArray());

            return response()->json(['message' => 'Usuario creado con éxito', 'data' => $user], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nombres' => 'sometimes|required|string|max:50',
            'apellidos' => 'sometimes|required|string|max:50',
            'cargo' => 'sometimes|required|string|max:50',
            'id_sede' => 'sometimes|required|exists:sedes,id_sede',
            'id_juzgado' => 'sometimes|required|exists:juzgados,id_juzgado',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'rol' => 'sometimes|required|string|in:admin,usuario',
        ]);

        $data = $request->except('password');
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'Usuario actualizado con éxito', 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }
}
