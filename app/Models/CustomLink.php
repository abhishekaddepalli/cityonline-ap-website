<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomLink extends Model
{
    protected $fillable = [
        'key',
        'label',
        'url',
        'description',
        'target',
    ];

    public static function getUrl(string $key, string $default = '#'): string
    {
        return static::where('key', $key)->value('url') ?? $default;
    }
}
