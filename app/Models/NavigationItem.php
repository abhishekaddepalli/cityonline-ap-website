<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'title_te',
        'url',
        'route',
        'link_type',
        'target',
        'icon',
        'badge',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(NavigationMenu::class, 'menu_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationItem::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }
}
