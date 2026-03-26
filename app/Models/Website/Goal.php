<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'image_alt',
        'tag1',
        'tag2',
        'tag3',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

  
}