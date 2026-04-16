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
    Schema::create('novels', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique(); // Đường dẫn chuẩn SEO
        $table->string('author')->nullable(); // Tác giả
        $table->string('illustrator')->nullable(); // Họa sĩ
        $table->text('description')->nullable(); // Tóm tắt
        $table->string('cover_image')->nullable(); // Ảnh bìa
        $table->enum('status', ['ongoing', 'completed', 'paused'])->default('ongoing'); // Trạng thái
        $table->unsignedBigInteger('views')->default(0); // Lượt xem
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novels');
    }
};
