<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Kategori - Lita Kitchen')]

class CategoriesPage extends Component
{
    public function render()
    {

        $categoryQuery = Category::query()->where('is_active', 1);

        return view('livewire.categories-page', [
            'categories' => $categoryQuery->paginate(4),
        ]);
    }
}
