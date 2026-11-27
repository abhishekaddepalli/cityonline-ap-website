<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'template',
        'excerpt',
        'content_blocks',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'canonical_url',
        'is_in_menu',
        'sort_order',
    ];

    protected $casts = [
        'content_blocks' => 'array',
        'published_at' => 'datetime',
        'is_in_menu' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
