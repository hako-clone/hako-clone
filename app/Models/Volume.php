<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// 🌟 Nhớ thêm dòng HasMany này ở trên cùng
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Volume extends Model
{
    use HasFactory;

    protected $fillable = [
        'novel_id',
        'title',
        'order',
    ];

    // Một Tập thuộc về một Truyện
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    // 🌟 THÊM ĐOẠN NÀY: Một Tập có nhiều Chương
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
}