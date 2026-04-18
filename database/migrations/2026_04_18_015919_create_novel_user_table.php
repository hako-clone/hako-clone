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
        Schema::create('novel_user', function (Blueprint $table) {
            $table->id();
            // Xoá user hoặc xoá truyện thì dữ liệu theo dõi cũng tự mất (onDelete cascade)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('novel_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novel_user');
    }
};
