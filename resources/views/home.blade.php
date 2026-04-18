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

    <div class="lg:w-2/3 bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300"">
        <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg inline-block">
            👍 Truyện Phổ Biến
        </h2>
        
        <div class="flex overflow-x-auto gap-4 pb-2 snap-x">
            @foreach($popularNovels as $novel)
                <a href="{{ route('novel.show', $novel->slug) }}" class="min-w-[150px] w-[150px] shrink-0 snap-start relative group">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="cover" class="w-full h-52 object-cover rounded shadow group-hover:opacity-80 transition">
                    @else
                        <div class="w-full h-52 bg-gray-300 rounded flex items-center justify-center">No Cover</div>
                    @endif
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-2 rounded-b text-white">
                        <h3 class="text-sm font-bold line-clamp-1" title="{{ $novel->title }}">{{ $novel->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        <div class="lg:w-2/3 bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300"">
            <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg inline-block">
                ⏱ Truyện Mới Cập Nhật
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($recentlyUpdated as $novel)
                    <div class="flex gap-3 border-b border-gray-200 pb-3">
                        <a href="{{ route('novel.show', $novel->slug) }}" class="w-24 shrink-0">
                            @if($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-32 object-cover rounded shadow hover:opacity-80 transition">
                            @else
                                <div class="w-full h-32 bg-gray-200 rounded"></div>
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
                                           class="text-blue-600 hover:text-orange-500 line-clamp-1 w-2/3">
                                            » {{ $chapter->title }}
                                        </a>
                                        <span class="text-gray-400 italic">{{ $chapter->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div><div class="mt-8 border-t border-gray-600 pt-4">
            {{ $recentlyUpdated->links() }}
            </div>
        </div>

        <div class="lg:w-1/3 space-y-6">
            
            <div class="lg:w-2/3 bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300"">
                <h2 class="bg-orange-500 text-white font-bold px-4 py-2 mb-4 rounded uppercase text-lg inline-block w-full">
                    ⭐ Top Truyện Đọc Nhiều
                </h2>
                <ul class="space-y-3">
                    @foreach($topReadNovels as $index => $novel)
                        <li class="flex items-center gap-2 border-b border-gray-100 pb-2">
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

            <div class="lg:w-2/3 bg-white dark:bg-gray-800 p-4 rounded shadow-sm transition-colors duration-300"">
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