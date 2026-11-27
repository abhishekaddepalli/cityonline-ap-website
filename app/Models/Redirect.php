<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'source_url',
        'target_url',
        'status_code',
        'is_active',
        'hits_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hits_count' => 'integer',
    ];
}
