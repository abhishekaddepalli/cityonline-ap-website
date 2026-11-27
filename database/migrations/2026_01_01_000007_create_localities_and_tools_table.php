<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sarpavaram, Ramanayyapeta, etc.
            $table->string('city')->default('Kakinada');
            $table->string('pincode', 10)->default('533001');
            $table->enum('coverage_status', ['available', 'expanding', 'planned'])->default('available');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('internet_tools', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // speed-test, ip-lookup, dns-lookup, ping-test, wifi-troubleshooter, bandwidth-calculator, traceroute, port-checker
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('endpoint_url')->nullable(); // Configurable LibreSpeed or custom speed endpoint
            $table->text('instructions')->nullable();
            $table->string('external_link')->nullable(); // For traceroute, port checker
            $table->boolean('is_external')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internet_tools');
        Schema::dropIfExists('localities');
    }
};
