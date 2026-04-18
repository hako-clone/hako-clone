@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded shadow transition-colors duration-300">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white border-b-2 border-yellow-500 mb-6 pb-2">
        🔍 Kết quả tìm kiếm cho: <span class="text-blue-600 dark:text-blue-400">"{{ $keyword }}"</span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Vòng lặp in ra kết quả --}}
        @forelse($results as $novel)
            <div class="border border-gray-200 dark:border-gray-700 p-4 rounded flex flex-col items-center hover:shadow-lg transition-all">
                
                <a href="{{ route('novel.show', $novel->slug) }}" class="w-full mb-3">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="cover" class="w-full h-64 rounded shadow object-cover">
                    @else
                        <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-sm text-gray-500 dark:text-gray-400 shadow">No cover</div>
                    @endif
                </a>

                <h3 class="font-bold text-center text-gray-800 dark:text-gray-100 line-clamp-2 leading-snug">
                    <a href="{{ route('novel.show', $novel->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                        {{ $novel->title }}
                    </a>
                </h3>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500 dark:text-gray-400 text-lg">Căng quá, thư viện không có bộ nào tên là "{{ $keyword }}" cả! 😭</p>
                <a href="/" class="text-blue-500 dark:text-blue-400 hover:underline mt-2 inline-block">Quay lại Trang chủ</a>
            </div>
        @endforelse

    </div>
</div>
@endsection