<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortVideo extends Model
{
    use HasFactory;

    protected $table = 'short_videos';

    protected $fillable = [
        'video_title',
        'video',
        'youtube_link',
        'button_name',
        'button_url',
        'status',
    ];
}