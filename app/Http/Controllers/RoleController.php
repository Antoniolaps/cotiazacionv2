<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('usuarios')->paginate(15);
        return view('roles.index', compact('roles'));
    }
}
