<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('content_en')->nullable()->after('content');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('excerpt_en')->nullable()->after('excerpt');
            $table->text('content_en')->nullable()->after('content');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('description_en')->nullable()->after('description');
            $table->text('facilities_en')->nullable()->after('facilities');
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('description_en')->nullable()->after('description');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('label_en')->nullable()->after('label');
        });
    }

    public function down(): void
    {
        Schema::table('pages', fn (Blueprint $table) => $table->dropColumn(['title_en', 'content_en']));
        Schema::table('news', fn (Blueprint $table) => $table->dropColumn(['title_en', 'excerpt_en', 'content_en']));
        Schema::table('courses', fn (Blueprint $table) => $table->dropColumn(['name_en', 'description_en', 'facilities_en']));
        Schema::table('document_categories', fn (Blueprint $table) => $table->dropColumn(['name_en', 'description_en']));
        Schema::table('documents', fn (Blueprint $table) => $table->dropColumn(['title_en', 'description_en']));
        Schema::table('menu_items', fn (Blueprint $table) => $table->dropColumn('label_en'));
    }
};
