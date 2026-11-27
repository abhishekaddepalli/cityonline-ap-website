<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broadband_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Standard, Premium, Ultra
            $table->string('slug')->unique(); // standard, premium, ultra
            $table->integer('speed_mbps'); // 60, 100, 150
            $table->integer('upload_speed_mbps')->nullable(); // symmetric
            $table->decimal('price_monthly', 10, 2); // 590, 767, 1180
            $table->string('base_price')->nullable(); // ₹500 + 18% GST
            $table->decimal('gst_percentage', 5, 2)->default(18.00);
            $table->string('gst_note')->default('Including GST');
            $table->string('note')->nullable(); // Two or three phones and a TV
            $table->string('data_allowance')->default('Unlimited Data');
            $table->string('fup_details')->default('No FUP');
            $table->string('installation_charges')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->string('badge_text')->nullable(); // Most Popular
            $table->json('features')->nullable(); // ["Unlimited data, no FUP", "Same upload & download speed", "Faults attended in 24 hours"]
            $table->string('cta_label')->default('Get This Plan');
            $table->string('cta_url')->nullable(); // Customer booking portal link
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadband_plans');
    }
};
