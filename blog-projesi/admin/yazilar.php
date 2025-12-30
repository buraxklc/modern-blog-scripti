<?php 
include 'header.php'; // Veritabanı ve Sidebar burada

// Silme İşlemi (Hızlıca buradan halledelim)
if (isset($_GET['sil'])) {
    $id = intval($_GET['sil']);
    $sorgu = $db->prepare("DELETE FROM posts WHERE id = ?");
    $sorgu->execute([$id]);
    header("Location: yazilar.php?durum=silindi");
}

$yazilar = $db->query("SELECT * FROM posts ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-6xl">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">İçerik Yönetimi</h1>
            <p class="text-slate-500">Toplam <?php echo count($yazilar); ?> makale yayında.</p>
        </div>
        <a href="yazi-ekle.php" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center gap-2">
            <span>+</span> Yeni Yazı Ekle
        </a>
    </header>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Görsel</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Başlık</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Durum</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($yazilar as $yazi): ?>
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <img src="../uploads/<?php echo $yazi['thumbnail'] ?: 'default.jpg'; ?>" 
                             class="w-12 h-12 rounded-lg object-cover bg-slate-100 shadow-sm">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900"><?php echo $yazi['title']; ?></div>
                        <div class="text-xs text-slate-400">/<?php echo $yazi['slug']; ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Yayında
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="yazi-duzenle.php?id=<?php echo $yazi['id']; ?>" class="p-2 text-slate-400 hover:text-indigo-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <a href="?sil=<?php echo $yazi['id']; ?>" onclick="return confirm('Siliyoz mu kanka? Geri dönüşü yok.')" class="p-2 text-slate-400 hover:text-red-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</main></body></html>