<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('study_program_accreditations', function (Blueprint $table) {
            $table->id();
            $table->string('source_id')->nullable()->unique();
            $table->string('study_level', 32);
            $table->string('decree_number')->nullable();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('rating', 80);
            $table->date('valid_until')->index();
            $table->string('source_document_name')->nullable();
            $table->string('certificate_path')->nullable();
            $table->string('certificate_file_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_program_accreditations');
    }
};
