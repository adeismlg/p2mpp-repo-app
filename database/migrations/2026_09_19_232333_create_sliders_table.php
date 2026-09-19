<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->string('image');
            $table->string('link_url')->nullable();
            $table->string('button_label')->nullable();
            $table->string('button_label_en')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
