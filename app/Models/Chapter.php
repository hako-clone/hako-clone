<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use HasFactory;

    // 1. Nhớ thêm tên cột chứa ảnh của bạn vào đây (tôi giả định là 'images')
    protected $fillable = [
        'volume_id',
        'title',
        'slug',
        'content', // 🌟 Dùng chữ 'content' giống trong Database
        'order',
        'views', // THÊM DÒNG NÀY (Nếu bên Filament bạn đặt tên cột là 'content' thì thay bằng 'content')
    ];

    // 2. 🌟 BỔ SUNG ĐOẠN NÀY ĐỂ ÉP KIỂU SANG MẢNG:
    protected $casts = [
        'content' => 'array', 
    ];

    public function volume(): BelongsTo
    {
        return $this->belongsTo(Volume::class);
    }
}