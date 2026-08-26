<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public string $title;

    public function __construct(string $title = 'sistemSIU')
    {
        $this->title = $title;
    }

    public function render(): View
    {
        return view('components.app-layout');
    }
}
