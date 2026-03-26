<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $table = 'website_settings';

    protected $fillable = [
        // Basic site identity
        'site_name',
        'site_web_logo',
        'site_mobile_logo',
        'site_logo_alt',
        'site_favicon',
        'punch_line',

        // Contact details
        'phone',
        'landline',
        'whats_app',
        'email',
        'fax',

        // Address
        'street_address',
        'city',
        'state',
        'country',
        'zip',

        // Social media
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'pinterest',

        // SEO / misc
        'sitemap_url',

        // Status
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active website settings (single row)
     */
    public static function active()
    {
        return self::where('is_active', true)->first();
    }
}
