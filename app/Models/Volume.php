<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Nhớ thêm dòng này để dùng chức năng BelongsTo
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class Volume extends Model
{
    use HasFactory;

    protected $fillable = [
        'novel_id',
        'title',
        'description',
        'cover_image',
    ];

    // Khai báo mối quan hệ: Tập này thuộc về Truyện nào
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}