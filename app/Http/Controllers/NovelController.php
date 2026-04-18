<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Novel;

class NovelController extends Controller
{
    public function show($slug)
    {
        // Đi tìm bộ truyện có cái slug trùng khớp, đồng thời "gói" luôn Thể loại và các Tập/Chương mang ra
        $novel = Novel::with(['categories', 'volumes.chapters'])
                      ->where('slug', $slug)
                      ->firstOrFail(); // Nếu gõ link bậy bạ không có truyện thì báo lỗi 404

        // Đẩy dữ liệu ra giao diện tên là 'novel'
        return view('novel', [
            'novel' => $novel
        ]);
    }
    public function toggleFollow($id)
    {
        $user = auth()->user();
        // Lệnh toggle cực kỳ thông minh của Laravel: Nếu chưa theo dõi thì nó Lưu, nếu đã lưu rồi thì nó Xóa
        $user->followedNovels()->toggle($id);
        
        return back(); // F5 lại trang hiện tại
    }
}