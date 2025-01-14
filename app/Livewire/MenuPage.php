<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Category;
use App\Models\Menu;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Menu - Lita Kitchen')]

class MenuPage extends Component
{

    use LivewireAlert;

    use WithPagination;

    #[Url]

    public $selected_categories = [];

    public $sort = 'latest';

    public function addToCart($menu_id){
        $total_count = CartManagement::addItemToCart($menu_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        
        $this->alert('success', 'Menu Berhasil Ditambahkan ke Keranjang!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
           ]);
    }

    public function render()
    {

        $menuQuery = Menu::query()->where('is_active', 1);

        if(!empty($this->selected_categories)){
            $menuQuery->whereIn('category_id', $this->selected_categories);
        }

        if($this->sort == 'latest'){
            $menuQuery->latest();
        }

        if($this->sort == 'price'){
            $menuQuery->orderBy('price');
        }

        return view('livewire.menu-page', [
            'menus' => $menuQuery->paginate(6),
            'categories' => Category::where('is_active', 1)-> get(['id', 'name', 'slug'])
        ]);
    }
}
