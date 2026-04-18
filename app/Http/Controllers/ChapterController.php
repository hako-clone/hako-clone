<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;

class ChapterController extends Controller
{
    public function show($novel_slug, $chapter_slug)
    {
        // 1. Tìm chương hiện tại đang đọc
        $chapter = Chapter::where('slug', $chapter_slug)
            ->whereHas('volume.novel', function($query) use ($novel_slug) {
                $query->where('slug', $novel_slug);
            })
            ->with('volume.novel') 
            ->firstOrFail();
            
        $chapter->increment('views');

        // 2. Lấy ID của bộ truyện hiện tại
        $novel_id = $chapter->volume->novel_id;

        // 3. Tìm chương TRƯỚC (Chương có ID nhỏ hơn chương hiện tại, cùng 1 bộ truyện)
        $prevChapter = Chapter::whereHas('volume', function($query) use ($novel_id) {
            $query->where('novel_id', $novel_id);
        })->where('id', '<', $chapter->id)->orderBy('id', 'desc')->first();

        // 4. Tìm chương SAU (Chương có ID lớn hơn chương hiện tại, cùng 1 bộ truyện)
        $nextChapter = Chapter::whereHas('volume', function($query) use ($novel_id) {
            $query->where('novel_id', $novel_id);
        })->where('id', '>', $chapter->id)->orderBy('id', 'asc')->first();

        // 🌟 5. TÌM TẤT CẢ CÁC CHƯƠNG CỦA BỘ TRUYỆN NÀY (Để làm menu thả xuống)
        $allChapters = Chapter::whereHas('volume', function($query) use ($novel_id) {
            $query->where('novel_id', $novel_id);
        })->orderBy('id', 'asc')->get();

        // 6. Ném tất cả dữ liệu ra giao diện
        return view('chapter', [
            'chapter' => $chapter,
            'prevChapter' => $prevChapter,
            'nextChapter' => $nextChapter,
            'allChapters' => $allChapters // 🌟 Đã truyền thêm biến này
        ]);
    }
}