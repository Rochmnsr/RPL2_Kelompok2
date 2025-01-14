<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Facades\Cookie;

class CartManagement {

    //add item
    static public function addItemToCart($menu_id){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;

        foreach($cart_items as $key => $item){
            if($item['menu_id'] == $menu_id){
                $existing_item = $key;
                break;
            }
        }

        if($existing_item !== null){
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * 
            $cart_items[$existing_item]['unit_amount'];
        }else {
        $menu = Menu::where('id', $menu_id)->first(['id', 'name', 'price', 'images']);
        if($menu){
            $cart_items[] = [
                'menu_id' => $menu_id,
                'name' => $menu->name,
                'image' => $menu->images[0],
                'quantity' => 1,
                'unit_amount' => $menu->price,
                'total_amount' =>$menu->price
            ];
        }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    // add item to cart with qty
    static public function addItemToCartWithQty($menu_id, $qty=1){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;

        foreach($cart_items as $key => $item){
            if($item['menu_id'] == $menu_id){
                $existing_item = $key;
                break;
            }
        }

        if($existing_item !== null){
            $cart_items[$existing_item]['quantity'] = $qty;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * 
            $cart_items[$existing_item]['unit_amount'];
        }else {
        $menu = Menu::where('id', $menu_id)->first(['id', 'name', 'price', 'images']);
        if($menu){
            $cart_items[] = [
                'menu_id' => $menu_id,
                'name' => $menu->name,
                'image' => $menu->images[0],
                'quantity' => $qty,
                'unit_amount' => $menu->price,
                'total_amount' =>$menu->price
            ];
        }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    //remove item
    static public function removeCartItem($menu_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['menu_id'] == $menu_id){
                unset($cart_items[$key]);
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }


    //add cart items to cookie
    static public function addCartItemsToCookie($cart_items){
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    //clear cart items from cookie
    static public function clearCartItems(){
        Cookie::queue(Cookie::forget('cart_items'));
    }

    //get all cart item from cookie
    static public function getCartItemsFromCookie(){
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if(!$cart_items){
            $cart_items = [];
        }

        return $cart_items;
    }

    //increase item quantity
    static public function increaseQuantityToCartItem($menu_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['menu_id'] == $menu_id){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]
                ['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }


    //decrease item quantity
    static public function decreaseQuantityToCartItem($menu_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['menu_id'] == $menu_id){
                if($cart_items[$key]['quantity'] > 1){
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]
                ['unit_amount'];
                }
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    //calculate total
    static public function calculateTotal($items){
        return array_sum(array_column($items, 'total_amount'));
    }
}