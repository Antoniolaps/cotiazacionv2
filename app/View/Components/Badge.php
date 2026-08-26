<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Badge extends Component
{
    public string $status;

    public function __construct(string $status = 'activo')
    {
        $this->status = strtolower($status);
    }

    public function colorClass(): string
    {
        return match ($this->status) {
            'pagada', 'aprobado', 'activo', 'completado' => 'bg-success',
            'pendiente', 'parcial' => 'bg-warning text-dark',
            'anulada', 'rechazado', 'inactivo', 'cancelada' => 'bg-danger',
            'cotizacion' => 'bg-info text-dark',
            default => 'bg-secondary',
        };
    }

    public function render(): View
    {
        return view('components.badge');
    }
}
