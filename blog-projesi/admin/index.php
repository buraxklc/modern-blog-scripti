<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
include 'header.php';
adminCheck(); // Güvenlik duvarı aktif!

// Basit bir istatistik çekelim
$postCount = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$catCount = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetim Paneli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 flex">

    
    <main class="flex-1 p-10">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Genel Bakış</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-slate-500 font-medium uppercase text-xs tracking-widest mb-2">Toplam İçerik</p>
                <h3 class="text-4xl font-black text-slate-900"><?php echo $postCount; ?></h3>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-slate-500 font-medium uppercase text-xs tracking-widest mb-2">Aktif Kategori</p>
                <h3 class="text-4xl font-black text-slate-900"><?php echo $catCount; ?></h3>
            </div>
        </div>
    </main>

</body>
</html>