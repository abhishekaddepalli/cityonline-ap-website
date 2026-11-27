<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->unique(); // header, footer_company, footer_support, footer_tools, footer_legal
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('navigation_menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->cascadeOnDelete();
            $table->string('title');
            $table->string('title_te')->nullable(); // Telugu label
            $table->string('url')->nullable();
            $table->string('route')->nullable();
            $table->string('link_type', 30)->default('internal'); // internal, external, tel, mailto, whatsapp
            $table->string('target', 20)->default('_self'); // _self, _blank
            $table->string('icon')->nullable();
            $table->string('badge')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('custom_links', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // pay_bill, new_connection, whatsapp, tel_support, etc.
            $table->string('label');
            $table->string('url');
            $table->string('description')->nullable();
            $table->string('target', 20)->default('_blank');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_links');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('navigation_menus');
    }
};
