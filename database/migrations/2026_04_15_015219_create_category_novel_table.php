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
        Schema::create('category_novel', function (Blueprint $table) {
            $table->id();
            // Nối ID của Thể loại
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            // Nối ID của Truyện
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_novel');
    }
};
