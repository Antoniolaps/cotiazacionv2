<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('role')->paginate(15);
        $roles = Role::where('activo', 1)->get();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:usuarios,username',
            'email' => 'nullable|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol_id' => 'required|exists:roles,id',
            'telefono' => 'nullable|string',
        ]);

        $data['password_hash'] = Hash::make($data['password']);
        unset($data['password']);

        $usuario = User::create($data);
        ActivityLogger::log('INSERT', 'usuarios', $usuario->id, null, ['username' => $usuario->username]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:usuarios,username,' . $usuario->id,
            'email' => 'nullable|email|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:6',
            'rol_id' => 'required|exists:roles,id',
            'telefono' => 'nullable|string',
            'estado' => 'boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
        }
        unset($data['password']);

        $old = $usuario->toArray();
        $usuario->update($data);
        ActivityLogger::log('UPDATE', 'usuarios', $usuario->id, $old, ['username' => $usuario->username]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $usuario)
    {
        $old = $usuario->toArray();
        $usuario->delete();
        ActivityLogger::log('DELETE', 'usuarios', $usuario->id, $old, null);

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}
