<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Home Page - Lita Kitchen')]

class HomePage extends Component
{
    public function render()
    {
        return view('livewire.home-page');
    }
}
