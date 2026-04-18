@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md p-6 mb-6 transition-colors duration-300"">
    <div class="flex gap-8">
        <div class="w-1/4 shrink-0">
            @if($novel->cover_image)
                <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full rounded shadow-lg object-cover">
            @else
                <div class="w-full h-80 bg-gray-200 flex items-center justify-center rounded shadow-lg">No Cover</div>
            @endif
        </div>

        <div class="w-3/4">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $novel->title }}</h1>
            
            <div class="mb-4">
                <p><strong>Tác giả:</strong> {{ $novel->author ?? 'Đang cập nhật' }}</p>
                <p><strong>Họa sĩ:</strong> {{ $novel->illustrator ?? 'Đang cập nhật' }}</p>
                <p><strong>Trạng thái:</strong> 
                    <span class="text-blue-600 font-bold">
                        {{ $novel->status == 'ongoing' ? 'Đang tiến hành' : 'Hoàn thành' }}
                    </span>
                </p>
            </div>

            <div class="flex gap-2 mb-4">
                @foreach ($novel->categories as $category)
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded shadow">
                        {{ $category->title }}
                    </span>
                @endforeach
            </div>

            <div class="mb-4">
                @auth
                    @php
                        $isFollowed = auth()->user()->followedNovels->contains($novel->id);
                    @endphp
                    
                    <form action="{{ route('novel.follow', $novel->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="{{ $isFollowed ? 'bg-gray-500 hover:bg-gray-600' : 'bg-red-500 hover:bg-red-600' }} text-white font-medium px-4 py-2 rounded shadow transition flex items-center gap-1 w-max">
                            {{ $isFollowed ? '💔 Bỏ theo dõi' : '❤ Theo dõi' }}
                        </button>
                    </form>
                @else
                    <button onclick="document.getElementById('loginModal').classList.remove('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-4 py-2 rounded shadow transition flex items-center gap-1 w-max">
                        ❤ Theo dõi
                    </button>
                @endauth
            </div>
            <div>
                <h3 class="font-bold text-lg border-b border-gray-300 pb-1 mb-2">Tóm tắt nội dung</h3>
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed text-sm">{{ $novel->description }}</div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-md p-6 mb-6 transition-colors duration-300"">
    <h2 class="text-2xl font-bold border-b-2 border-blue-500 mb-4 pb-2">Danh sách Tập</h2>

    @forelse ($novel->volumes as $volume)
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 p-2 rounded mb-2">
                📚 {{ $volume->title }}
            </h3>
            
            <ul class="pl-4 list-disc list-inside">
                @forelse ($volume->chapters as $chapter)
                    <li class="py-1 border-b border-dashed border-gray-200 dark:border-gray-700 last:border-0">
                        <a href="{{ route('chapter.show', ['novel_slug' => $novel->slug, 'chapter_slug' => $chapter->slug]) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">
                            {{ $chapter->title }}
                        </a>
                        <span class="text-xs text-gray-400 float-right">{{ $chapter->created_at->format('d/m/Y') }}</span>
                    </li>
                @empty
                    <p class="text-gray-500 text-sm italic">Tập này chưa có chương nào.</p>
                @endforelse
            </ul>
        </div>
    @empty
        <p class="text-gray-500">Truyện này hiện chưa có tập nào được đăng.</p>
    @endforelse
</div>

<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-60 z-[100] hidden flex items-center justify-center transition-opacity">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-2xl w-full max-w-md relative transform scale-100 transition-transform">
        
        <button onclick="document.getElementById('loginModal').classList.add('hidden')" class="absolute top-3 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none">
            &times;
        </button>
        
        <div class="text-center">
            <div class="bg-orange-100 text-orange-500 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                🔒
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Bạn chưa đăng nhập!</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6 text-sm">
                Tính năng "Theo dõi truyện" cần có tài khoản để lưu lại. Vui lòng đăng nhập hoặc đăng ký để trải nghiệm tốt nhất nhé!
            </p>
            
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('loginModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Đóng lại
                </button>
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                    Đến trang Đăng nhập
                </a>
            </div>
        </div>
    </div>
</div>
@endsection