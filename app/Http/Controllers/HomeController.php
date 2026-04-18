<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\Category; // Gọi thêm bảng Thể loại
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Ép Laravel hiển thị thời gian bằng Tiếng Việt (VD: "30 phút trước")
        Carbon::setLocale('vi'); 

        // 1. Truyện Phổ Biến (Tạm thời lấy ngẫu nhiên 8 truyện cho đa dạng)
        $popularNovels = Novel::inRandomOrder()->take(8)->get();

        // 2. Mới Cập Nhật (Lấy 6 truyện có chương mới, kèm theo 3 chương mới nhất của mỗi truyện)
        $recentlyUpdated = Novel::whereHas('chapters')
            ->with(['chapters' => function($query) {
                $query->orderBy('created_at', 'desc')->take(3);
            }])
            ->orderBy('updated_at', 'desc')
            ->paginate(6); // 🌟 Thay take(6)->get() bằng paginate(6)

        // 3. Top Truyện Đọc Nhiều (Tính tổng lượt view của tất cả các chương trong truyện)
        $topReadNovels = Novel::withSum('chapters', 'views')
            ->orderBy('chapters_sum_views', 'desc')
            ->take(10)
            ->get();

        // 4. Lấy danh sách Thể loại
        $categories = Category::all();

        return view('home', [
            'popularNovels' => $popularNovels,
            'recentlyUpdated' => $recentlyUpdated,
            'topReadNovels' => $topReadNovels,
            'categories' => $categories
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $results = Novel::query()
                        ->where('title', 'like', '%' . $keyword . '%')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('search', [
            'keyword' => $keyword,
            'results' => $results
        ]);
    }
    // 🌟 THÊM HÀM NÀY ĐỂ XỬ LÝ LỌC THỂ LOẠI
    public function category($slug)
    {
        // 1. Tìm thể loại dựa vào đường dẫn (slug)
        $category = Category::where('slug', $slug)->firstOrFail();

        // 2. Lấy tất cả truyện thuộc thể loại này (có sắp xếp mới nhất)
        $novels = $category->novels()->orderBy('created_at', 'desc')->get();

        // 3. Ném dữ liệu ra trang giao diện
        return view('category', [
            'category' => $category,
            'novels' => $novels
        ]);
    }
    public function ranking()
    {
        // Tôi thấy trong log DB cũ của bạn, bạn dùng chapters_sum_views. 
        // Lệnh dưới đây sẽ lấy truyện có tổng view cao nhất từ trên xuống dưới (Phân trang 20 truyện/trang)
        $rankedNovels = \App\Models\Novel::withSum('chapters as total_views', 'views')
                            ->orderByDesc('total_views')
                            ->paginate(20);

        return view('ranking', compact('rankedNovels'));
    }
    public function following()
    {
        // Lấy thông tin user đang đăng nhập
        $user = auth()->user();

        // Lấy danh sách truyện user này đã theo dõi, phân trang 12 truyện/trang
        $followedNovels = $user->followedNovels()->orderBy('novel_user.created_at', 'desc')->paginate(12);

        // Ném dữ liệu ra giao diện
        return view('following', compact('followedNovels'));
    }
}