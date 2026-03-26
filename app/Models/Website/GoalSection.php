<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoalSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_text',
        'title',
        'description',
        'status'
    ];

  
}