<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Website\PaymentSetting;

class PaymentSettingController extends BaseController
{

    /**
     * Get all payment settings
     */
    public function index()
    {
        $settings = PaymentSetting::latest()->get();

        return $this->success($settings, 'Payment settings fetched successfully');
    }


    /**
     * Store or update payment setting
     */
    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'mode' => 'required|in:test,live'
        ]);

        $setting = PaymentSetting::updateOrCreate(
            ['provider' => $request->provider],
            [
                'mode' => $request->mode,
                'test_key' => $request->test_key,
                'test_secret' => $request->test_secret,
                'live_key' => $request->live_key,
                'live_secret' => $request->live_secret,
                'status' => $request->status ?? 0
            ]
        );

        return $this->success($setting, 'Payment setting saved successfully');
    }


    /**
     * Get single payment setting
     */
    public function show($provider)
    {
        $setting = PaymentSetting::where('provider', $provider)->first();

        if (!$setting) {
            return $this->error('Payment setting not found');
        }

        return $this->success($setting, 'Payment setting fetched');
    }


    /**
     * Enable / Disable payment gateway
     */
    public function updateStatus(Request $request, $id)
    {
        $setting = PaymentSetting::find($id);

        if (!$setting) {
            return $this->error('Payment gateway not found');
        }

        $setting->status = $request->status;
        $setting->save();

        return $this->success($setting, 'Payment status updated');
    }

}