<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Light Novel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Script giữ cấu hình Dark Mode
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-[#f4f5f6] dark:bg-gray-900 text-[#333] dark:text-gray-200 font-sans transition-colors duration-300">

    <nav class="bg-[#212529] dark:bg-black text-white shadow-md sticky top-0 z-[60] transition-colors duration-300">
        <div class="container mx-auto px-4 py-3">
            
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-orange-500 hover:text-orange-400 flex items-center gap-2">
                    🔥 Cổng Light Novel
                </a>

                <div class="flex items-center gap-4 md:gap-6">
                    
                    <div class="relative flex items-center">
                        <div class="hidden md:block relative">
                            <form action="{{ route('search') }}" method="GET">
                                <input type="text" name="q" placeholder="Tìm kiếm truyện..." class="px-4 py-1.5 rounded-full text-black focus:outline-none w-48 lg:w-64 text-sm">
                                <button type="submit" class="absolute right-3 top-1.5 text-gray-500 hover:text-orange-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </form>
                        </div>

                        <button id="mobile-search-toggle" class="md:hidden text-gray-300 hover:text-orange-500 p-1 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>

                        <div id="mobile-search-menu" class="absolute top-full right-0 mt-3 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 p-3 hidden transition-all z-50">
                            <form action="{{ route('search') }}" method="GET" class="flex items-center gap-2">
                                <input type="text" name="q" placeholder="Nhập tên truyện..." class="w-full bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded px-3 py-2 text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded text-sm font-bold">
                                    Tìm
                                </button>
                            </form>
                        </div>
                    </div>
                    <button id="theme-toggle" class="text-gray-300 hover:text-white transition">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    </button>
                    @auth
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-300">Chào, {{ auth()->user()->name }}</span>
                            
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                <a href="/admin" class="bg-orange-500 text-white px-3 py-1.5 rounded text-sm font-bold hover:bg-orange-600 transition">
                                    ⚙️ Quản trị
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-red-500 text-sm font-medium transition">Đăng xuất</button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-4 items-center">
                            <a href="{{ route('login') }}" class="hover:text-yellow-400 font-medium transition-colors">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 transition font-medium">Đăng ký</a>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-600 dark:border-gray-800 flex items-center gap-8 font-medium">
                
                <div class="relative group">
                    <button class="flex items-center gap-1 hover:text-orange-500 transition pb-2 focus:outline-none">
                        ☰ THỂ LOẠI
                    </button>
                    <div class="absolute left-0 top-full mt-0 hidden w-[600px] bg-white dark:bg-gray-800 shadow-2xl rounded-b border-t-2 border-t-orange-500 border border-gray-100 dark:border-gray-700 group-hover:block z-50 transition-all">
                        <div class="grid grid-cols-3 gap-3 p-5">
                            @if(isset($globalCategories))
                                @foreach($globalCategories as $cat)
                                    <a href="{{ route('category.show', $cat->slug) }}" class="text-gray-700 dark:text-gray-300 hover:text-orange-500 dark:hover:text-orange-400 text-sm transition">
                                        {{ $cat->title }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <a href="/xep-hang" class="hover:text-orange-500 transition flex items-center gap-1 pb-2">
                    📊 XẾP HẠNG
                </a>

                <a href="/theo-doi" class="hover:text-orange-500 transition flex items-center gap-1 pb-2">
                    🔖 THEO DÕI
                </a>
                
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <button id="scrollToTopBtn" class="fixed bottom-6 right-6 bg-orange-500 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center hidden hover:bg-orange-600 transition z-50">
        ↑
    </button>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }
        var themeToggleBtn = document.getElementById('theme-toggle');
        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });

        // Script Cuộn lên đầu trang
        let scrollToTopBtn = document.getElementById("scrollToTopBtn");
        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                scrollToTopBtn.classList.remove("hidden");
            } else {
                scrollToTopBtn.classList.add("hidden");
            }
        };
        scrollToTopBtn.onclick = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        // 🌟 ĐÃ THÊM: Script cho Nút Kính lúp Mobile 🌟
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('mobile-search-toggle');
            const searchMenu = document.getElementById('mobile-search-menu');
            
            if (searchBtn && searchMenu) {
                // Bấm vào kính lúp thì mở/đóng menu tìm kiếm
                searchBtn.addEventListener('click', function(e) {
                    e.stopPropagation(); 
                    searchMenu.classList.toggle('hidden');
                });

                // Bấm ra ngoài vùng menu thì tự động ẩn đi
                document.addEventListener('click', function(e) {
                    if (!searchMenu.contains(e.target) && !searchBtn.contains(e.target)) {
                        searchMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>