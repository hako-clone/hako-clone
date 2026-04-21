@extends('layouts.app')

@section('content')
<div id="top-banner" class="relative w-full mb-8 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
    <a href="#">
        <img src="{{ asset('images/banner.gif') }}" alt="Banner Khuyến Mãi" class="w-full h-auto max-h-[160px] object-cover rounded-xl border border-gray-200 opacity-95 hover:opacity-100 transition-opacity">
    </a>
    <button onclick="document.getElementById('top-banner').style.display='none'" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold shadow-xl hover:bg-red-600 hover:scale-110 transition-all">
        ✕
    </button>
</div>

<div class="space-y-8">
    <div class="w-full bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300">
        <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg inline-block">
            👍 Truyện Phổ Biến
        </h2>
        
        <div class="flex overflow-x-auto gap-4 pb-2 snap-x">
            @foreach($popularNovels as $novel)
                <a href="{{ route('novel.show', $novel->slug) }}" class="min-w-[150px] w-[150px] shrink-0 snap-start relative group">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="cover" class="w-full h-52 object-cover rounded shadow group-hover:opacity-80 transition">
                    @else
                        <div class="w-full h-52 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded flex items-center justify-center">No Cover</div>
                    @endif
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-2 rounded-b text-white">
                        <h3 class="text-sm font-bold line-clamp-1" title="{{ $novel->title }}">{{ $novel->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        <div class="lg:w-2/3 bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300">
            <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg inline-block">
                @if(request()->anyFilled(['keyword', 'category', 'status']))
                    🔍 Kết Quả Tìm Kiếm
                @else
                    ⏱ Truyện Mới Cập Nhật
                @endif
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($recentlyUpdated as $novel)
                    <div class="flex gap-3 border-b border-gray-200 dark:border-gray-700 pb-3">
                        <a href="{{ route('novel.show', $novel->slug) }}" class="w-24 shrink-0">
                            @if($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-32 object-cover rounded shadow hover:opacity-80 transition">
                            @else
                                <div class="w-full h-32 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            @endif
                        </a>

                        <div class="flex-1 flex flex-col justify-start">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm line-clamp-2 hover:text-orange-500 mb-2">
                                <a href="{{ route('novel.show', $novel->slug) }}">{{ $novel->title }}</a>
                            </h3>
                            
                            <div class="space-y-1">
                                @foreach($novel->chapters as $chapter)
                                    <div class="flex justify-between items-center text-xs">
                                        <a href="{{ route('chapter.show', ['novel_slug' => $novel->slug, 'chapter_slug' => $chapter->slug]) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-orange-500 dark:hover:text-orange-400 line-clamp-1 w-2/3">
                                            » {{ $chapter->title }}
                                        </a>
                                        <span class="text-gray-400 dark:text-gray-500 italic">{{ $chapter->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-4">
                {{ $recentlyUpdated->links() }}
            </div>
        </div>

        <div class="lg:w-1/3 space-y-6">
            
            <div class="w-full bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300">
                <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Lọc Truyện
                </h2>
                
                <form action="{{ route('home') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-gray-600 dark:text-gray-400 text-xs font-bold mb-1 uppercase tracking-wider">Từ khóa</label>
                        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tên truyện, tác giả..." class="w-full bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition">
                    </div>

                    <div>
                        <label class="block text-gray-600 dark:text-gray-400 text-xs font-bold mb-1 uppercase tracking-wider">Thể loại</label>
                        <select name="category" class="w-full bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition">
                            <option value="">-- Tất cả thể loại --</option>
                            @if(isset($globalCategories))
                                @foreach($globalCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->title ?? $cat->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-600 dark:text-gray-400 text-xs font-bold mb-1 uppercase tracking-wider">Tình trạng</label>
                        <select name="status" class="w-full bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition">
                            <option value="">-- Tất cả tình trạng --</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Đang tiến hành</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Tạm ngưng</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-gray-200 dark:bg-gray-700 hover:bg-orange-500 dark:hover:bg-orange-600 text-gray-800 dark:text-white font-bold py-2 px-4 rounded transition duration-300 flex items-center justify-center gap-2 mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Tìm Kiếm
                    </button>
                </form>
            </div>

            <div class="w-full bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300">
                <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg inline-block w-full">
                    ⭐ Top Truyện Đọc Nhiều
                </h2>
                <ul class="space-y-3">
                    @foreach($topReadNovels as $index => $novel)
                        <li class="flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white 
                                {{ $index == 0 ? 'bg-red-500' : ($index == 1 ? 'bg-orange-400' : ($index == 2 ? 'bg-yellow-400' : 'bg-gray-400')) }}">
                                {{ $index + 1 }}
                            </span>
                            <a href="{{ route('novel.show', $novel->slug) }}" class="text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-orange-500 line-clamp-1">
                                {{ $novel->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="w-full bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300">
                <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg inline-block w-full">
                    🏷 Thể Loại Truyện
                </h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-orange-500 flex items-center gap-1 border border-gray-200 dark:border-gray-600 p-2 rounded hover:bg-orange-50 dark:hover:bg-gray-700 transition">
                            <span class="text-orange-500">»</span> {{ $category->title }}
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection