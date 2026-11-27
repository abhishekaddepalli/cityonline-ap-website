<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('faq_key')->nullable()->unique(); // plans, new, bill, down, area
            $table->string('category', 50)->default('general'); // plans, technical, billing, coverage
            $table->text('question_en');
            $table->text('question_te')->nullable();
            $table->longText('answer_en');
            $table->longText('answer_te')->nullable();
            $table->json('keywords')->nullable(); // search triggers
            $table->json('action_links')->nullable(); // quick action buttons
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
