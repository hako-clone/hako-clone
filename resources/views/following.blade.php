@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors duration-300">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white border-b-2 border-orange-500 mb-6 pb-2 flex items-center gap-2">
        🔖 Tủ Sách Của Bạn
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($followedNovels as $novel)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex flex-col hover:shadow-xl transition-all relative group bg-gray-50 dark:bg-gray-700">
                
                <a href="{{ route('novel.show', $novel->slug) }}" class="relative block overflow-hidden">
                    
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="Cover" class="w-full h-64 object-cover transform group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-64 bg-gray-200 dark:bg-gray-800 flex items-center justify-center border-b border-gray-300 dark:border-gray-700">
                            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Chưa có ảnh bìa</span>
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 text-white font-bold bg-orange-500 px-4 py-2 rounded-full transition-all transform translate-y-4 group-hover:translate-y-0">
                            Đọc tiếp
                        </span>
                    </div>
                </a>

                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100 line-clamp-2 mb-2">
                        <a href="{{ route('novel.show', $novel->slug) }}" class="hover:text-orange-500 transition">
                            {{ $novel->title }}
                        </a>
                    </h3>
                    
                    <div class="mt-auto pt-3 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Tác giả: {{ $novel->author ?? 'Đang cập nhật' }}
                        </span>
                        
                        <form action="{{ route('novel.follow', $novel->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 text-sm font-medium transition flex items-center gap-1 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded" title="Bỏ lưu truyện này">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Tủ sách của bạn đang trống!</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Hãy dạo quanh Cổng Light Novel và tìm cho mình một bộ truyện yêu thích nhé.</p>
                <a href="{{ route('home') }}" class="bg-orange-500 text-white px-6 py-2.5 rounded shadow-lg hover:bg-orange-600 transition font-medium">
                    🔍 Đi tìm truyện ngay
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $followedNovels->links() }}
    </div>
</div>
@endsection