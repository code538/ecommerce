<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\WebsiteSetting;
use App\Models\Website\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends BaseController
{
    /**
     * Get active website settings (Frontend: Blade / React / Flutter)
     * GET /api/website-settings
     */
    public function show()
    {
        //dd('okk');
        $settings = WebsiteSetting::active();
        $faq = Faq::where('faq_slug', 'contact')->where('is_active', true)->get();
        $data =[
            'settings'=>$settings,
            'faqs'  => $faq
        ];
        if (! $settings) {
            return $this->error('Website settings not found', null, 404);
        }

        return $this->success($data, 'Website settings fetched');
    }

    /**
     * Create website settings (Admin – usually once)
     * POST /api/admin/website-settings
     */
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name'         => 'nullable|string|max:255',

            'site_web_logo'     => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_mobile_logo'  => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_logo_alt'     => 'nullable|string|max:255',
            'site_favicon'      => 'nullable|image|mimes:png,ico|max:1024',
            'punch_line'        => 'nullable|string|max:255',

            'phone'             => 'nullable|string|max:50',
            'landline'          => 'nullable|string|max:50',
            'whats_app'          => 'nullable|string|max:50',
            'email'             => 'nullable|email',
            'fax'               => 'nullable|string|max:50',

            'street_address'    => 'nullable|string',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'country'           => 'nullable|string|max:100',
            'zip'               => 'nullable|string|max:20',

            'facebook'          => 'nullable|url',
            'twitter'           => 'nullable|url',
            'linkedin'          => 'nullable|url',
            'instagram'         => 'nullable|url',
            'pinterest'         => 'nullable|url',

            'sitemap_url'       => 'nullable|url',
            'is_active'         => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $data = $request->except([
            'site_web_logo',
            'site_mobile_logo',
            'site_favicon',
        ]);

        // Deactivate old settings
        WebsiteSetting::where('is_active', true)->update(['is_active' => false]);

        // Upload images
        if ($request->hasFile('site_web_logo')) {
            $data['site_web_logo'] = $request
                ->file('site_web_logo')
                ->store('website/logo', 'public');
        }

        if ($request->hasFile('site_mobile_logo')) {
            $data['site_mobile_logo'] = $request
                ->file('site_mobile_logo')
                ->store('website/logo', 'public');
        }

        if ($request->hasFile('site_favicon')) {
            $data['site_favicon'] = $request
                ->file('site_favicon')
                ->store('website/logo', 'public');
        }

        $settings = WebsiteSetting::create($data);

        return $this->success($settings, 'Website settings created', 201);
    }

    public function edit (){
        $settings = WebsiteSetting::first();

        if (! $settings) {
            return $this->error('Website settings not found', null, 404);
        }

        return $this->success($settings, 'Website settings get succesfuly');
    }


   

    public function update(Request $request, int $id)
    {
        $settings = WebsiteSetting::find($id);

        if (! $settings) {
            return $this->error('Website settings not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'site_name'         => 'nullable|string|max:255',

            'site_web_logo'     => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_mobile_logo'  => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_logo_alt'     => 'nullable|string|max:255',
            'site_favicon'      => 'nullable|image|mimes:png,ico|max:1024',
            'punch_line'        => 'nullable|string|max:255',

            'phone'             => 'nullable|string|max:50',
            'landline'          => 'nullable|string|max:50',
            'whats_app'          => 'nullable|string|max:50',
            'email'             => 'nullable|email',
            'fax'               => 'nullable|string|max:50',

            'street_address'    => 'nullable|string',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'country'           => 'nullable|string|max:100',
            'zip'               => 'nullable|string|max:20',

            'facebook'          => 'nullable|url',
            'twitter'           => 'nullable|url',
            'linkedin'          => 'nullable|url',
            'instagram'         => 'nullable|url',
            'pinterest'         => 'nullable|url',

            'sitemap_url'       => 'nullable|url',
            'is_active'         => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $data = $request->except([
            'site_web_logo',
            'site_mobile_logo',
            'site_favicon',
        ]);

        /**
         * Replace Web Logo
         */
        if ($request->hasFile('site_web_logo')) {
            if ($settings->site_web_logo && Storage::disk('public')->exists($settings->site_web_logo)) {
                Storage::disk('public')->delete($settings->site_web_logo);
            }

            $data['site_web_logo'] = $request
                ->file('site_web_logo')
                ->store('website/logo', 'public');
        }

        /**
         * Replace Mobile Logo
         */
        if ($request->hasFile('site_mobile_logo')) {
            if ($settings->site_mobile_logo && Storage::disk('public')->exists($settings->site_mobile_logo)) {
                Storage::disk('public')->delete($settings->site_mobile_logo);
            }

            $data['site_mobile_logo'] = $request
                ->file('site_mobile_logo')
                ->store('website/logo', 'public');
        }

        /**
         * Replace Favicon
         */
        if ($request->hasFile('site_favicon')) {
            if ($settings->site_favicon && Storage::disk('public')->exists($settings->site_favicon)) {
                Storage::disk('public')->delete($settings->site_favicon);
            }

            $data['site_favicon'] = $request
                ->file('site_favicon')
                ->store('website/logo', 'public');
        }

        /**
         * Ensure only one active record
         */
        if ($request->has('is_active') && $request->is_active) {
            WebsiteSetting::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $settings->update($data);

        return $this->success($settings, 'Website settings updated');
    }

}
