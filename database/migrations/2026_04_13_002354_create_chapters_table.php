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
    Schema::create('chapters', function (Blueprint $table) {
        $table->id();
        $table->foreignId('volume_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->string('slug');
        $table->longText('content'); // Nội dung truyện
        $table->integer('order')->default(0); // Số thứ tự chương
        $table->unsignedBigInteger('views')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
