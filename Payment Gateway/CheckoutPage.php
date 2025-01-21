<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Address;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

#[Title('Checkout')]

class CheckoutPage extends Component
{
    public $name;
    public $phone;
    public $address;
    public $city;
    public $zip_code;
    public $payment_method;

    public function placeOrder() {

        $this->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required'
        ]);
    
        $cart_items = CartManagement::getCartItemsFromCookie();   
        
        $line_items = [];
    
        foreach ($cart_items as $item) {
            $line_items[] = [
                'id' => $item['menu_id'],
                'price' => $item['unit_amount'],
                'quantity' => $item['quantity'],
                'name' => $item['name'],
            ];
        }
    
        // Create Order
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->grand_total = CartManagement::calculateTotal($cart_items);
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'IDR';
        $order->payment_method =$this->payment_method;
        $order->shipping_method = 'none';
        $order->notes = 'Order telah dibuat oleh ' . auth()->user()->name;
        $order->save();
    
        // Save Address
        $address = new Address();
        $address->name = $this->name;
        $address->phone = $this->phone;
        $address->address = $this->address;
        $address->city = $this->city;
        $address->zip_code = $this->zip_code;
        $order->address()->save($address);

        $order->items()->createMany($cart_items);
    
        // Midtrans Configuration
        if($this->payment_method == 'MidTrans'){
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    
        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->grand_total,
            ],
            'customer_details' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => $this->phone,
                'shipping_address' => [
                    'name' => $this->name,
                    'address' => $this->address,
                    'city' => $this->city,
                    'postal_code' => $this->zip_code,
                    'phone' => $this->phone,
                ],
            ],
            'item_details' => $line_items,
            CartManagement::clearCartItems(),
        ];
    
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snap_token = $snapToken;
            $order->save();
    
            $redirect_url = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken;
            return redirect()->to($redirect_url);
            } 
        catch (\Exception $e) {
            // Handle error
            session()->flash('error', 'Failed to create Midtrans payment. ' . $e->getMessage());
            return redirect()->route('cancel');
        }
    }
    $redirect_url = route('success');
    $order->save();
    $address->order_id = $order->id;
    $address->save();
    CartManagement::clearCartItems();
    return redirect($redirect_url);

}

    public function callback(Request $request){
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        if($hashed == $request->signature_key){
            if($request->transaction_status == 'capture'){
                $order = Order::find($request->order_id);
                $order->update(['status' => 'Paid']);
            }
        }
    }


    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateTotal($cart_items);

        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }

}