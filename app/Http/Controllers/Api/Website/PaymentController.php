<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Models\Website\Order;
use App\Models\Website\PaymentSetting;
use App\Models\Website\Payment;

class PaymentController extends BaseController
{

    public function createRazorpayOrder($order_id)
    {
        //dd($order_id);
        $order = Order::findOrFail($order_id);

        $setting = PaymentSetting::where('provider','razorpay')
                    ->where('status',1)
                    ->first();

        $key = $setting->mode == 'live'
                ? $setting->live_key
                : $setting->test_key;

        $secret = $setting->mode == 'live'
                ? $setting->live_secret
                : $setting->test_secret;

        $api = new Api($key,$secret);

        $razorpayOrder = $api->order->create([

            'receipt' => $order->order_number,
            'amount' => $order->total_amount * 100,
            'currency' => 'INR'

        ]);

        return $this->success([
            'razorpay_order_id'=>$razorpayOrder['id'],
            'key'=>$key
        ],'Razorpay order created');

    }

    public function verifyPayment(Request $request)
    {

        $request->validate([
            'order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required'
        ]);

        $order = Order::where('id',$request->order_id)
                    ->where('user_id',auth()->id())
                    ->first();

        if(!$order){
            return $this->error('Order not found');
        }

        if($order->payment_status == 'paid'){
            return $this->error('Order already paid');
        }

        // Get Razorpay keys from DB
        $setting = PaymentSetting::where('provider','razorpay')
                    ->where('status',1)
                    ->first();

        $key = $setting->mode == 'live'
                ? $setting->live_key
                : $setting->test_key;

        $secret = $setting->mode == 'live'
                ? $setting->live_secret
                : $setting->test_secret;

        $api = new Api($key,$secret);

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        try {

            $api->utility->verifyPaymentSignature($attributes);

        } catch(SignatureVerificationError $e) {

            return $this->error('Payment verification failed');

        }

        // Save payment
        $payment = Payment::create([

            'order_id' => $order->id,
            'payment_gateway' => 'razorpay',
            'payment_id' => $request->razorpay_payment_id,
            'transaction_id' => $request->razorpay_order_id,
            'amount' => $order->total_amount,
            'status' => 'paid'

        ]);

        $order->payment_status = 'paid';
        $order->save();

        return $this->success($payment,'Payment verified successfully');

    }

}
