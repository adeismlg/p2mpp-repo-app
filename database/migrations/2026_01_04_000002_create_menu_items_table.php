<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menu_items')
                ->cascadeOnDelete();
            $table->string('label');
            // 'route'    => arahkan ke named route internal (home, documents.index, dst)
            // 'page'     => arahkan ke halaman dinamis (tabel pages, by slug)
            // 'external' => URL bebas (bisa ke sistem lain seperti SIJAMU)
            $table->string('type', 20)->default('external');
            $table->string('route_name')->nullable();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('url')->nullable();
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
