<?php 
require_once 'db.php'; 

$cat_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kategori adını 'categories' tablosundan alıyoruz
$katSorgu = $db->prepare("SELECT name FROM categories WHERE id = ?");
$katSorgu->execute([$cat_id]);
$category = $katSorgu->fetch(PDO::FETCH_ASSOC);

if (!$category) { header("Location: index.php"); exit; }

// Yazıları 'post' tablosundan çekiyoruz
$postSorgu = $db->prepare("SELECT * FROM post WHERE category_id = ? ORDER BY id DESC");
$postSorgu->execute([$cat_id]);
$posts = $postSorgu->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= $category['name'] ?> Arşivi</title>
</head>
<body class="bg-white text-black font-sans">

    <nav class="max-w-5xl mx-auto px-6 py-10 border-b-2 border-black">
        <a href="index.php" class="font-black text-3xl tracking-tighter">BLOG.</a>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-16">
        <h2 class="text-6xl font-black tracking-tighter uppercase mb-16 border-l-8 border-black pl-6">
            <?= $category['name'] ?>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <?php foreach ($posts as $p): ?>
            <div class="border border-zinc-100 p-8 hover:border-black transition-colors duration-300">
                <h3 class="text-2xl font-bold tracking-tight">
                    <a href="yazi.php?id=<?= $p['id'] ?>" class="hover:underline">
                        <?= htmlspecialchars($p['title']) ?>
                    </a>
                </h3>
                <p class="text-zinc-500 mt-4 text-sm leading-relaxed">
                    <?= mb_substr(strip_tags($p['content']), 0, 150) ?>...
                </p>
                <div class="mt-6">
                    <a href="yazi.php?id=<?= $p['id'] ?>" class="text-xs font-black uppercase tracking-widest border-b-2 border-black pb-1">Oku</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>