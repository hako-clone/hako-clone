<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Novel;
use App\Models\Category;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request) // 🌟 Thêm Request $request vào đây
    {
        // Ép Laravel hiển thị thời gian bằng Tiếng Việt
        Carbon::setLocale('vi'); 

        // 1. TRUYỆN PHỔ BIẾN (Giữ nguyên)
        $popularNovels = Novel::inRandomOrder()->take(8)->get();

        // 2. LOGIC TÌM KIẾM NÂNG CAO & TRUYỆN MỚI CẬP NHẬT
        // Khởi tạo query: Chỉ lấy những truyện đã có chương
        $query = Novel::whereHas('chapters')
            ->with(['chapters' => function($q) {
                $q->orderBy('created_at', 'desc')->take(3);
            }]);

        // --- Bắt đầu lọc theo yêu cầu từ Form Advanced Search ---
        
        // Lọc theo từ khóa (Tên truyện hoặc Tác giả)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('author', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo Thể loại
        if ($request->filled('category')) {
            $categoryId = $request->category;
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Lọc theo Tình trạng (Trạng thái)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Chốt hạ: Sắp xếp theo ngày cập nhật mới nhất và Phân trang
        // 🌟 withQueryString() giúp giữ lại các bộ lọc khi người dùng bấm sang trang 2, 3
        $recentlyUpdated = $query->orderBy('updated_at', 'desc')
                                 ->paginate(6)
                                 ->withQueryString();

        // 3. TOP TRUYỆN ĐỌC NHIỀU (Giữ nguyên)
        $topReadNovels = Novel::withSum('chapters as total_views', 'views')
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get();

        // 4. LẤY DANH SÁCH THỂ LOẠI (Cho menu lọc)
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
        // Đây là hàm search đơn giản (thường dùng cho ô search trên Navbar)
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

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $novels = $category->novels()->orderBy('created_at', 'desc')->get();

        return view('category', [
            'category' => $category,
            'novels' => $novels
        ]);
    }

    public function ranking()
    {
        $rankedNovels = Novel::withSum('chapters as total_views', 'views')
                            ->orderByDesc('total_views')
                            ->paginate(20);

        return view('ranking', compact('rankedNovels'));
    }

    public function following()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!$user) return redirect()->route('login');

        $followedNovels = $user->followedNovels()
                                ->orderBy('novel_user.created_at', 'desc')
                                ->paginate(12);

        return view('following', compact('followedNovels'));
    }
}