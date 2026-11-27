<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'logo_url',
        'website_url',
        'category',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    /**
     * Resilient auto-schema check: ensures table exists and initial seed brands exist
     * without requiring SSH or terminal commands on cPanel.
     */
    public static function ensureTableExists(): void
    {
        try {
            if (!Schema::hasTable('brands')) {
                Schema::create('brands', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('logo_url')->nullable();
                    $table->string('website_url')->nullable();
                    $table->string('category')->nullable()->default('Partner');
                    $table->integer('sort_order')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });

                self::seedDefaultBrands();
            } else if (self::count() === 0) {
                self::seedDefaultBrands();
            }
        } catch (\Throwable $e) {
            // Silently fail if DB is not connected yet
        }
    }

    public static function seedDefaultBrands(): void
    {
        $defaultBrands = [
            [
                'name' => 'Cisco Systems',
                'logo_url' => '/images/brands/cisco.svg',
                'website_url' => 'https://www.cisco.com',
                'category' => 'Network Hardware',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Juniper Networks',
                'logo_url' => '/images/brands/juniper.svg',
                'website_url' => 'https://www.juniper.net',
                'category' => 'Routing Infrastructure',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'TP-Link',
                'logo_url' => '/images/brands/tplink.svg',
                'website_url' => 'https://www.tp-link.com',
                'category' => 'Wi-Fi & Optical CPE',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'MikroTik',
                'logo_url' => '/images/brands/mikrotik.svg',
                'website_url' => 'https://mikrotik.com',
                'category' => 'Core Routing',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'D-Link',
                'logo_url' => '/images/brands/dlink.svg',
                'website_url' => 'https://www.dlink.com',
                'category' => 'Enterprise Switching',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Netgear',
                'logo_url' => '/images/brands/netgear.svg',
                'website_url' => 'https://www.netgear.com',
                'category' => 'Broadband Hardware',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($defaultBrands as $b) {
            self::updateOrCreate(['name' => $b['name']], $b);
        }
    }
}
