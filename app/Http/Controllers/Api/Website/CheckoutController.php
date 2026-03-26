<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Website\Order;
use App\Models\Website\OrderItem;
use App\Models\Website\Product;
use App\Models\Website\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends BaseController
{

    // public function checkout(Request $request)
    // {
       
    //     $request->validate([
    //         // 'receiver_name' => 'required',
    //         // 'receiver_phone' => 'required',
    //         // 'address' => 'required',
    //         // 'city' => 'required',
    //         // 'state' => 'required',
    //         // 'pincode' => 'required',
    //         'products'=>'required|array'

    //     ]);

    //     $total = 0;

    //     foreach($request->products as $item){

    //         $product = Product::find($item['product_id']);

    //         if (!$product) {
    //             return $this->error('Product not found: '.$item['product_id']);
    //         }

    //         $price = $product->sale_price ?? $product->price;

    //         $total += $price * $item['qty'];

    //     }

    //     $order = Order::create([

    //         'user_id' => auth()->id(),
    //         'order_number' => 'ORD'.time(),

    //         'customer_name'=>auth()->user()->name ?? 'Guest',
    //         'email'=>auth()->user()->email ?? 'Guest@example.com',
    //         'phone'=>auth()->user()->phone ?? null,

    //         // 'receiver_name' => $request->receiver_name,
    //         // 'receiver_phone' => $request->receiver_phone,
    //         // 'address' => $request->address,
    //         // 'city' => $request->city,
    //         // 'state' => $request->state,
    //         // 'pincode' => $request->pincode,

    //         'total_amount'=>$total,

    //         //'payment_method'=>$request->payment_method

    //     ]);

    //     foreach($request->products as $item){

    //         $product = Product::find($item['product_id']);

    //         $price = $product->sale_price ?? $product->price;

    //         OrderItem::create([

    //             'order_id'=>$order->id,
    //             'product_id'=>$product->id,
    //             'qty'=>$item['qty'],
    //             'price'=>$price

    //         ]);

    //     }

    //     return $this->success($order,'Order created successfully');

    // }

    public function checkout(Request $request)
    {
        $request->validate([
            'products' => 'required|array'
        ]);

        $total = 0;

        foreach ($request->products as $item) {

            $product = Product::find($item['product_id']);

            if (!$product) {
                return $this->error('Product not found: ' . $item['product_id']);
            }

            // If variant exists
            if (!empty($item['variant_id'])) {

                $variant = ProductVariant::where('id',$item['variant_id'])
                            ->where('product_id',$product->id)
                            ->first();

                if (!$variant) {
                    return $this->error('Variant not found');
                }

                $price = $variant->sale_price ?? $variant->price;

            } else {

                $price = $product->sale_price ?? $product->price;

            }

            $total += $price * $item['qty'];
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD'.time(),

            'customer_name' => auth()->user()->name ?? 'Guest',
            'email' => auth()->user()->email ?? 'Guest@example.com',
            'phone' => auth()->user()->phone ?? null,

            'total_amount' => $total
        ]);

        foreach ($request->products as $item) {

            $product = Product::find($item['product_id']);

            if (!empty($item['variant_id'])) {

                $variant = ProductVariant::find($item['variant_id']);
                $price = $variant->sale_price ?? $variant->price;

            } else {

                $price = $product->sale_price ?? $product->price;

            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'variant_id' => $item['variant_id'] ?? null,
                'qty' => $item['qty'],
                'price' => $price
            ]);
        }

        return $this->success($order,'Order created successfully');
    }

    public function checkoutCod(Request $request, $id)
    {
         $request->validate([
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'payment_method'=>'required'

        ]);

        $order = Order::where('id',$id)
                    ->where('user_id',auth()->id())
                    ->first();

        if(!$order){
            return $this->error('Order not found');
        }

        $order->receiver_name = $request->receiver_name;
        $order->receiver_phone = $request->receiver_phone;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->state = $request->state;
        $order->pincode = $request->pincode;
        $order->payment_method = $request->payment_method;
        $order->save();

        return $this->success($order,'Order placed successfully');

    }

    public function myOrders(Request $request)
    {

        $orders = Order::where('user_id', auth()->id())
                    ->latest()
                    ->get();

        return $this->success($orders,'My orders fetched');

    }


    // Order details
    // public function orderDetails($id)
    // {

    //     $order = Order::with([
    //         'items.product.images',
    //         'items.product.variants',
    //         'payment'
    //     ])
    //     ->where('id',$id)
    //     ->where('user_id',auth()->id())
    //     ->first();

    //     if(!$order){
    //         return $this->error('Order not found');
    //     }

    //     return $this->success($order,'Order details fetched');

    // }

    public function orderDetails($id)
    {

        $order = Order::with([
            'items.product.images',
            'items.variant',   // load only ordered variant
            'payment'
        ])
        ->where('id',$id)
        ->where('user_id',auth()->id())
        ->first();

        if(!$order){
            return $this->error('Order not found');
        }

        return $this->success($order,'Order details fetched');

    }

    public function myCompleateOrders(Request $request)
    {

        $orders = Order::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        return $this->success($orders,'My orders fetched');

    }

    public function myCompleateOrdersDetails($id)
    {

        $order = Order::with([
            'items.product.images',
            'items.product.variants',
            'payment'
        ])
        ->where('id',$id)
        ->where('payment_status', 'paid')
        ->where('user_id',auth()->id())
        ->first();

        if(!$order){
            return $this->error('Order not found');
        }

        return $this->success($order,'Order details fetched');

    }

}