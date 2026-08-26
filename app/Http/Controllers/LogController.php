<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActividad;

class LogController extends Controller
{
    public function index()
    {
        $logs = LogActividad::with(['usuario', 'role'])
            ->orderBy('fecha', 'desc')
            ->paginate(25);

        return view('logs.index', compact('logs'));
    }
}
