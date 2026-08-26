<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Services\ActivityLogger;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Cliente::query();

        if ($search) {
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('cedula_ruc', 'LIKE', "%{$search}%");
        }

        $clientes = $query->paginate(15);
        return view('clientes.index', compact('clientes', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:clientes,codigo',
            'nombre' => 'required|string|max:150',
            'cedula_ruc' => 'nullable|string|unique:clientes,cedula_ruc',
            'tipo_cliente' => 'required|in:regular,mayorista,corporativo',
            'telefono' => 'nullable|string',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
        ]);

        $cliente = Cliente::create($data);
        ActivityLogger::log('INSERT', 'clientes', $cliente->id, null, $cliente->toArray());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado con éxito.');
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:clientes,codigo,' . $cliente->id,
            'nombre' => 'required|string|max:150',
            'cedula_ruc' => 'nullable|string|unique:clientes,cedula_ruc,' . $cliente->id,
            'tipo_cliente' => 'required|in:regular,mayorista,corporativo',
            'telefono' => 'nullable|string',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'activo' => 'boolean',
        ]);

        $old = $cliente->toArray();
        $cliente->update($data);
        ActivityLogger::log('UPDATE', 'clientes', $cliente->id, $old, $cliente->toArray());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $old = $cliente->toArray();
        $cliente->delete();
        ActivityLogger::log('DELETE', 'clientes', $cliente->id, $old, null);

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}
