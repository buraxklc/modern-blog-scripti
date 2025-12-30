<?php 
require_once 'inc/db.php'; 
require_once 'inc/functions.php';

// Site ayarlarını çek
$settings = $db->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

// Yazıları Çek
$posts = $db->query("SELECT posts.*, categories.name as cat_name 
                    FROM posts 
                    LEFT JOIN categories ON posts.category_id = categories.id 
                    WHERE status = 'published' 
                    ORDER BY posts.id DESC")->fetchAll(PDO::FETCH_ASSOC);

// MANTIĞI SIFIRLADIK:
$featured = $posts[0] ?? null;   // En üstteki dev manşet
$articles = array_slice($posts, 1); // Geri kalan her şey aşağıya
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings['site_title']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fff; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="text-slate-900 selection:bg-indigo-600 selection:text-white">

    <nav class="border-b border-slate-100 sticky top-0 bg-white/90 backdrop-blur-md z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="index.php" class="flex items-center">
                <?php if(!empty($settings['site_logo'])): ?>
                    <img src="assets/<?php echo $settings['site_logo']; ?>" class="h-8 w-auto">
                <?php else: ?>
                    <span class="text-2xl font-black tracking-tighter uppercase italic">BLOG<span class="text-indigo-600">.</span>PRO</span>
                <?php endif; ?>
            </a>
            <div class="hidden md:flex gap-10 text-[11px] font-black uppercase tracking-widest text-slate-400">
                <a href="index.php" class="text-indigo-600">ANASAYFA</a>
                <a href="kategori.php" class="hover:text-slate-900 transition">KATEGORİLER</a>
                <a href="yazi.php" class="hover:text-slate-900 transition">TÜM YAZILAR</a>
            </div>
            
            
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-6 py-10">
        <?php if($featured): ?>
        <div class="w-full group relative overflow-hidden rounded-[3rem] bg-slate-100 shadow-2xl shadow-slate-200/50">
            <a href="yazi.php?slug=<?php echo $featured['slug']; ?>">
                <div class="aspect-[21/9] md:aspect-[21/7] w-full">
                    <img src="uploads/<?php echo $featured['thumbnail']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent flex flex-col justify-end p-8 md:p-16">
                    <span class="bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full w-fit mb-6">ÖNE ÇIKAN İÇERİK</span>
                    <h2 class="text-white text-4xl md:text-6xl font-extrabold tracking-tighter leading-tight max-w-4xl">
                        <?php echo $featured['title']; ?>
                    </h2>
                    <p class="text-slate-300 mt-6 line-clamp-2 max-w-2xl text-base md:text-lg font-medium opacity-80">
                        <?php echo $featured['meta_desc']; ?>
                    </p>
                </div>
            </a>
        </div>
        <?php endif; ?>
    </section>

    <main class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex items-center gap-6 mb-16">
            <h2 class="text-4xl font-black tracking-tighter uppercase italic">Makaleler<span class="text-indigo-600">.</span></h2>
            <div class="h-[1px] flex-1 bg-slate-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-20">
            <?php if(count($articles) > 0): foreach($articles as $art): ?>
            <article class="group">
                <a href="yazi.php?slug=<?php echo $art['slug']; ?>">
                    <div class="relative aspect-video rounded-[2.5rem] overflow-hidden bg-slate-50 mb-8 shadow-sm group-hover:shadow-2xl shadow-indigo-100/50 transition-all duration-500">
                        <img src="uploads/<?php echo $art['thumbnail']; ?>" class="w-full h-full object-cover scale-105 group-hover:scale-100 transition-transform duration-700">
                        <div class="absolute top-6 left-6">
                            <span class="px-4 py-1.5 bg-white/90 backdrop-blur text-[9px] font-black uppercase tracking-widest text-slate-900 rounded-full shadow-sm">
                                <?php echo $art['cat_name']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="px-2 space-y-4">
                        <h3 class="text-2xl font-bold text-slate-900 leading-tight group-hover:text-indigo-600 transition-colors">
                            <?php echo $art['title']; ?>
                        </h3>
                        <p class="text-slate-400 text-sm font-medium line-clamp-2 leading-relaxed italic">
                            <?php echo $art['meta_desc']; ?>
                        </p>
                        <div class="flex items-center gap-3 pt-2 text-[10px] font-black text-slate-300 uppercase tracking-widest">
                            <span><?php echo date('d.m.Y', strtotime($art['created_at'])); ?></span>
                            <span class="w-8 h-[1px] bg-slate-100"></span>
                            <span class="text-indigo-500 opacity-0 group-hover:opacity-100 transition-all">OKUMAYA BAŞLA →</span>
                        </div>
                    </div>
                </a>
            </article>
            <?php endforeach; else: ?>
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] text-slate-300 font-bold uppercase tracking-[0.5em]">
                    İçerikler Hazırlanıyor...
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="border-t border-slate-50 py-24 text-center">
        <p class="text-slate-400 text-[11px] font-black uppercase tracking-[0.4em]">
            © <?php echo date('Y'); ?> <?php echo $settings['site_footer']; ?>
        </p>
    </footer>

</body>
</html>