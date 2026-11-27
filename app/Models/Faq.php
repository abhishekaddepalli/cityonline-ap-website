<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'faq_key',
        'category',
        'question_en',
        'question_te',
        'answer_en',
        'answer_te',
        'keywords',
        'action_links',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'keywords' => 'array',
        'action_links' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
