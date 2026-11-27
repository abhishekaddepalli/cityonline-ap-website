<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternetTool extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'endpoint_url',
        'instructions',
        'external_link',
        'is_external',
        'is_active',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
