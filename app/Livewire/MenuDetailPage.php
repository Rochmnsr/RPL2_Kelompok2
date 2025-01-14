<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Menu;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Title;


#[Title('Menu Detail - Lita Kitchen')]
class MenuDetailPage extends Component

{

    use LivewireAlert;
    public $slug;

    public $quantity = 1;

    public function mount($slug){
        $this->slug = $slug;
    }

    public function increaseQty(){
        $this->quantity++;
    }

    public function decreaseQty(){
        if($this->quantity > 1){
            $this->quantity--;
        }
    }

    public function addToCart($menu_id){
        $total_count = CartManagement::addItemToCartWithQty($menu_id, $this->quantity);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        
        $this->alert('success', 'Menu Berhasil Ditambahkan ke Keranjang!', [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
           ]);
    }

    public function render()
    {
        return view('livewire.menu-detail-page', [
            'menu' => Menu::where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
