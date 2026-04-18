@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-colors duration-300">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white border-b-2 border-orange-500 mb-6 pb-2 flex items-center gap-2">
        🏆 Bảng Xếp Hạng Lượt Xem
    </h2>

    <div class="space-y-4">
        @foreach($rankedNovels as $index => $novel)
            <div class="flex items-center gap-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-700 transition">
                
                <div class="w-12 text-center shrink-0">
                    @php
                        $rank = ($rankedNovels->currentPage() - 1) * $rankedNovels->perPage() + $index + 1;
                        $rankColor = match($rank) {
                            1 => 'bg-red-500 text-white',
                            2 => 'bg-orange-500 text-white',
                            3 => 'bg-yellow-400 text-white',
                            default => 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'
                        };
                    @endphp
                    <span class="{{ $rankColor }} text-lg font-bold w-10 h-10 flex items-center justify-center rounded-full mx-auto shadow">
                        {{ $rank }}
                    </span>
                </div>

                <a href="{{ route('novel.show', $novel->slug) }}" class="shrink-0">
                    <img src="{{ $novel->cover_image ? asset('storage/' . $novel->cover_image) : asset('images/no-cover.jpg') }}" alt="Cover" class="w-16 h-20 object-cover rounded shadow">
                </a>

                <div class="flex-grow min-w-0">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 line-clamp-1">
                        <a href="{{ route('novel.show', $novel->slug) }}" class="hover:text-orange-500 transition">
                            {{ $novel->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate mt-1">
                        Tác giả: {{ $novel->author ?? 'Đang cập nhật' }}
                    </p>
                    <div class="flex gap-2 mt-2">
                        @foreach ($novel->categories->take(3) as $category)
                            <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded">
                                {{ $category->title }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="shrink-0 text-right min-w-[100px] hidden md:block">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">Tổng lượt xem</div>
                    <div class="text-orange-500 font-bold text-xl">
                        👁 {{ number_format($novel->total_views) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $rankedNovels->links() }}
    </div>
</div>
@endsection