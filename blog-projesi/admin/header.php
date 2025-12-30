<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
// adminCheck(); // Giriş kontrolün buraya
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli | DevBlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Sidebar yumuşak geçiş */
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen relative overflow-x-hidden">

    <div class="md:hidden fixed top-4 right-4 z-[60]">
        <button id="menu-toggle" class="p-3 bg-indigo-600 text-white rounded-2xl shadow-xl active:scale-90 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <aside id="sidebar" class="w-72 bg-white border-r border-slate-200 fixed md:sticky top-0 h-screen p-6 flex flex-col shrink-0 z-50 -translate-x-full md:translate-x-0 shadow-2xl md:shadow-none">
        
        <div class="mb-10 px-2 flex items-center space-x-3">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg"></div>
            <span class="text-xl font-extrabold tracking-tight italic">DEV.PANEL</span>
        </div>
        
        <nav class="space-y-1 flex-1">
            <a href="index.php" class="flex items-center space-x-3 p-3 rounded-xl transition <?php echo $current_page == 'index.php' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50' ?>">
                <span>Dashboard</span>
            </a>
            <a href="yazi-ekle.php" class="flex items-center space-x-3 p-3 rounded-xl transition <?php echo $current_page == 'yazi-ekle.php' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50' ?>">
                <span>Yeni Yazı Ekle</span>
            </a>
            <a href="yazilar.php" class="flex items-center space-x-3 p-3 rounded-xl transition <?php echo $current_page == 'yazilar.php' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50' ?>">
                <span>Tüm Yazılar</span>
            </a>
            <a href="kategoriler.php" class="flex items-center space-x-3 p-3 rounded-xl transition <?php echo $current_page == 'kategoriler.php' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50' ?>">
                <span>Kategoriler</span>
            </a>
            <a href="ayarlar.php" class="flex items-center space-x-3 p-3 rounded-xl transition <?php echo $current_page == 'ayarlar.php' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:bg-slate-50' ?>">
                <span>Ayarlar</span>
            </a>
        </nav>

        <div class="pt-6 border-t border-slate-100">
            <a href="logout.php" class="flex items-center space-x-3 p-3 rounded-xl text-red-500 hover:bg-red-50 transition font-semibold">
                <span>Çıkış Yap</span>
            </a>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-slate-900/40 z-40 hidden backdrop-blur-sm transition-opacity"></div>

    <main class="flex-1 p-6 md:p-10 overflow-y-auto w-full">

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            // Tailwind sınıflarını değiştiriyoruz
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            // Mobilde body kaymasını engelle
            document.body.classList.toggle('overflow-hidden');
        }

        if(menuToggle) {
            menuToggle.addEventListener('click', toggleSidebar);
        }

        if(overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }
    </script>