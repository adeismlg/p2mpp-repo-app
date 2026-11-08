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
        Schema::create('accreditation_evidence', function (Blueprint $table) {
            $table->id();
            $table->string('instrument')->nullable();
            $table->string('scope', 32)->default('institution');
            $table->string('assessment_entity')->nullable();
            $table->string('criterion_code', 80)->index();
            $table->text('criterion_name');
            $table->string('evidence_title');
            $table->string('evidence_path')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 32)->default('draft')->index();
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accreditation_evidence');
    }
};
