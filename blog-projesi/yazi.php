<?php 
require_once 'db.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Tablo isimlerini 'post' ve 'categories' olarak güncelledik
$sorgu = $db->prepare("SELECT post.*, categories.name AS category_name 
                       FROM post 
                       JOIN categories ON post.category_id = categories.id 
                       WHERE post.id = ?");
$sorgu->execute([$id]);
$yazi = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$yazi) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= htmlspecialchars($yazi['title']) ?></title>
</head>
<body class="bg-white text-black font-sans selection:bg-black selection:text-white">

    <nav class="max-w-3xl mx-auto px-6 py-12 flex justify-between items-center border-b-2 border-black">
        <a href="index.php" class="font-black text-3xl tracking-tighter uppercase">BLOG.</a>
        <a href="index.php" class="text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white border border-black px-4 py-2 transition">Geri</a>
    </nav>

    <article class="max-w-3xl mx-auto px-6 py-20">
        <header class="mb-12">
            <a href="kategori.php?id=<?= $yazi['category_id'] ?>" class="text-xs font-bold uppercase text-zinc-400 hover:text-black">
                / <?= $yazi['category_name'] ?>
            </a>
            <h1 class="text-5xl font-black tracking-tight mt-4 leading-tight">
                <?= htmlspecialchars($yazi['title']) ?>
            </h1>
        </header>

        <div class="prose prose-zinc prose-xl max-w-none text-zinc-900 leading-relaxed">
            <?= nl2br($yazi['content']) ?>
        </div>
    </article>

</body>
</html>