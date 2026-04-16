<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('volumes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('novel_id')->constrained()->onDelete('cascade');
        $table->string('title'); // VD: Tập 1, Tập 2...
        $table->string('cover_image')->nullable();
        $table->integer('order')->default(0); // Số thứ tự tập
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volumes');
    }
};
