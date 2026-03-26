<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Website\Order;
use App\Models\Website\OrderTrack;

class OrderController extends BaseController
{

    // Get all orders
    public function index()
    {

        $orders = Order::with('payment')
                    ->latest()
                    ->get();

        return $this->success($orders,'Orders fetched');

    }


    // Order details
    public function show($id)
    {

        $order = Order::with([
            'items.product',
            'payment',
            'tracks'
        ])->find($id);

        if(!$order){
            return $this->error('Order not found');
        }

        return $this->success($order,'Order details fetched');

    }


    // Update order status
    public function updateStatus(Request $request,$id)
    {

        $order = Order::find($id);

        if(!$order){
            return $this->error('Order not found');
        }

        $order->order_status = $request->status;
        $order->save();

        OrderTrack::create([

            'order_id'=>$order->id,
            'status'=>$request->status,
            'message'=>$request->message

        ]);

        return $this->success($order,'Order status updated');

    }


    // Update payment status
    public function updatePayment(Request $request,$id)
    {

        $order = Order::find($id);

        if(!$order){
            return $this->error('Order not found');
        }

        $order->payment_status = $request->payment_status;

        $order->save();

        return $this->success($order,'Payment status updated');

    }

}