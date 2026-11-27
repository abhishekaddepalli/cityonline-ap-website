<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BroadbandPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'speed_mbps',
        'upload_speed_mbps',
        'price_monthly',
        'base_price',
        'gst_percentage',
        'gst_note',
        'note',
        'data_allowance',
        'fup_details',
        'installation_charges',
        'is_popular',
        'badge_text',
        'features',
        'cta_label',
        'cta_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'speed_mbps' => 'integer',
        'upload_speed_mbps' => 'integer',
        'price_monthly' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'is_popular' => 'boolean',
        'features' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
