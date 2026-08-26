<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class StatCard extends Component
{
    public string $title;
    public string $value;
    public string $icon;
    public string $color;

    public function __construct(string $title, string $value, string $icon = 'bi-bar-chart', string $color = 'primary')
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->color = $color;
    }

    public function render(): View
    {
        return view('components.stat-card');
    }
}
