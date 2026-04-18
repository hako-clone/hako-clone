@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow transition-colors duration-300">
    <h2 class="text-2xl font-bold border-b-2 border-orange-500 mb-6 pb-2 uppercase">
        🏷 Thể loại: <span class="text-orange-500">{{ $category->name }}</span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        @forelse($novels as $novel)
            <div class="border border-gray-200 p-4 rounded flex flex-col items-center hover:shadow-lg transition-all group">
                
                <a href="{{ route('novel.show', $novel->slug) }}" class="w-full mb-3 relative overflow-hidden rounded">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="cover" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-sm text-gray-500">No cover</div>
                    @endif
                </a>

                <h3 class="font-bold text-center text-gray-800 line-clamp-2 leading-snug">
                    <a href="{{ route('novel.show', $novel->slug) }}" class="hover:text-orange-500 transition">
                        {{ $novel->title }}
                    </a>
                </h3>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500 text-lg">Hiện tại chưa có bộ truyện nào thuộc thể loại này.</p>
                <a href="/" class="text-blue-500 hover:underline mt-2 inline-block">Quay lại Trang chủ</a>
            </div>
        @endforelse

    </div>
</div>
@endsection