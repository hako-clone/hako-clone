@extends('layouts.app')

@section('content')
<div class="bg-gray-900 min-h-screen pb-10">
    
    <div id="reading-header" class="bg-gray-800 text-white p-4 text-center shadow-md mb-6 sticky top-0 z-50 transition-all duration-300 ease-in-out">
        <h1 class="text-xl font-bold text-yellow-400">
            <a href="{{ route('novel.show', $chapter->volume->novel->slug) }}" class="hover:underline">
                {{ $chapter->volume->novel->title }}
            </a>
        </h1>
        <h2 class="text-md text-gray-300 mt-1">{{ $chapter->volume->title }} - {{ $chapter->title }}</h2>
    </div>

    <div class="max-w-4xl mx-auto flex flex-wrap items-center justify-center gap-3 mb-6 px-4">
        @if($prevChapter)
            <a href="{{ route('chapter.show', ['novel_slug' => $prevChapter->volume->novel->slug, 'chapter_slug' => $prevChapter->slug]) }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600 transition flex items-center gap-1">
                ⬅ <span class="hidden sm:inline">Chương trước</span>
            </a>
        @else
            <button disabled class="bg-gray-800 text-gray-500 px-4 py-2 rounded cursor-not-allowed flex items-center gap-1">
                ⬅ <span class="hidden sm:inline">Chương trước</span>
            </button>
        @endif

        @if(isset($allChapters))
        <select onchange="window.location.href=this.value" class="bg-gray-800 border border-gray-600 text-gray-200 text-sm md:text-base px-3 py-2 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-48 md:w-64 truncate">
            @foreach($allChapters as $chap)
                <option value="{{ route('chapter.show', ['novel_slug' => $chapter->volume->novel->slug, 'chapter_slug' => $chap->slug]) }}" 
                    {{ $chapter->id == $chap->id ? 'selected' : '' }}>
                    {{ $chap->title }}
                </option>
            @endforeach
        </select>
        @endif

        @if($nextChapter)
            <a href="{{ route('chapter.show', ['novel_slug' => $nextChapter->volume->novel->slug, 'chapter_slug' => $nextChapter->slug]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition flex items-center gap-1">
                <span class="hidden sm:inline">Chương sau</span> ➡
            </a>
        @else
            <button disabled class="bg-gray-800 text-gray-500 px-4 py-2 rounded cursor-not-allowed flex items-center gap-1">
                <span class="hidden sm:inline">Chương sau</span> ➡
            </button>
        @endif
    </div>

    <div class="max-w-4xl mx-auto bg-black flex flex-col items-center">
        @if($chapter->content && is_array($chapter->content))
            @foreach($chapter->content as $block)
                @if($block['type'] === 'text_block')
                    <div class="w-full text-gray-200 p-6 md:p-10 text-lg leading-loose whitespace-pre-wrap bg-gray-800 my-4 rounded shadow-lg prose prose-invert max-w-none">
                        {!! $block['data']['text_content'] !!} 
                    </div>
                @endif
                @if($block['type'] === 'comic_block')
                    @if(isset($block['data']['pages']) && is_array($block['data']['pages']))
                        @foreach($block['data']['pages'] as $imagePath)
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Trang truyện" class="w-full h-auto block m-0 p-0" loading="lazy">
                        @endforeach
                    @endif
                @endif
            @endforeach
        @else
            <div class="text-gray-200 p-6 text-lg">
                Nội dung chương đang được cập nhật...
            </div>
        @endif
    </div>

    <div class="max-w-3xl mx-auto mt-8 flex flex-wrap items-center justify-center gap-3 px-4">
        @if($prevChapter)
            <a href="{{ route('chapter.show', ['novel_slug' => $prevChapter->volume->novel->slug, 'chapter_slug' => $prevChapter->slug]) }}" class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-600 transition">
                ⬅ Chương trước
            </a>
        @else
            <button disabled class="bg-gray-800 text-gray-500 px-6 py-2 rounded cursor-not-allowed">
                ⬅ Chương trước
            </button>
        @endif

        @if($nextChapter)
            <a href="{{ route('chapter.show', ['novel_slug' => $nextChapter->volume->novel->slug, 'chapter_slug' => $nextChapter->slug]) }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-500 transition">
                Chương sau ➡
            </a>
        @else
            <button disabled class="bg-gray-800 text-gray-500 px-6 py-2 rounded cursor-not-allowed">
                Chương sau ➡
            </button>
        @endif
    </div>
</div>

<button id="scrollToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-8 right-8 bg-blue-600 text-white p-3 rounded-full shadow-xl hover:bg-blue-500 hover:-translate-y-1 transition-all duration-300 opacity-0 invisible z-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
    // --- KHAI BÁO BIẾN ---
    const readingHeader = document.getElementById('reading-header');
    const mainNavbar = document.querySelector('nav'); 
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    // Thiết lập hiệu ứng mượt mà ban đầu
    if(mainNavbar) mainNavbar.style.transition = 'transform 0.3s ease-in-out';
    if(readingHeader) readingHeader.style.transition = 'transform 0.3s ease-in-out';

    window.addEventListener('scroll', function() {
        let currentScroll = window.scrollY || document.documentElement.scrollTop;

        // 1. HIỆN/ẨN NÚT CUỘN LÊN (Vị trí > 300px)
        if (currentScroll > 300) {
            scrollToTopBtn.classList.remove('opacity-0', 'invisible');
            scrollToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            scrollToTopBtn.classList.remove('opacity-100', 'visible');
            scrollToTopBtn.classList.add('opacity-0', 'invisible');
        }

        // 🌟 2. CHẾ ĐỘ ĐỌC TẬP TRUNG (IMMERSIVE MODE)
        // Khi cuộn xuống quá 50px, ẩn toàn bộ menu
        if (currentScroll > 50) {
            if (readingHeader) readingHeader.style.transform = 'translateY(-150%)'; // Đẩy cao hẳn lên để ẩn
            if (mainNavbar) mainNavbar.style.transform = 'translateY(-100%)';
        } 
        // Chỉ khi quay lại sát đầu trang mới hiện
        else {
            if (readingHeader) readingHeader.style.transform = 'translateY(0)';
            if (mainNavbar) mainNavbar.style.transform = 'translateY(0)';
        }
    });
</script>
@endsection