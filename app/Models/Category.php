<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// Model Category đại diện cho bảng categories trong database
class Category extends Model
{
    use HasFactory;

    // ĐÂY LÀ CHÌA KHÓA: Khai báo đúng 3 cột chúng ta đang dùng trong Form!
    protected $fillable = [
        'title', 
        'slug',
        'description',
    ];

    // Hàm khai báo mối quan hệ (Một thể loại có nhiều truyện)
    public function novels(): BelongsToMany
    {
        return $this->belongsToMany(Novel::class);
    }
}