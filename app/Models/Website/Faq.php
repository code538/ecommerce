<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'faq_type',
        'faq_slug',
        'faq_question',
        'question_meta',
        'faq_answer',
        'faq_answer_meta',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
