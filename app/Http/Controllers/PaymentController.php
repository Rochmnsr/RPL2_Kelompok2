<?php

namespace App\Http\Controllers;

use App\Livewire\CheckoutPage;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{   
    public function callback(Request $request){
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        if($hashed == $request->signature_key){
            if($request->transaction_status == 'capture'){
                $order = CheckoutPage::find($request->order_id);
                $order->update(['status' => 'Paid']);
            }
        }
    }
}
