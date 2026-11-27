<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'section_key',
        'name',
        'title',
        'subtitle',
        'badge_text',
        'description',
        'content',
        'bg_color',
        'text_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
