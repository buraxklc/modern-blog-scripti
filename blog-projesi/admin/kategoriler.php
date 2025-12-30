<?php 
include 'header.php'; // Veritabanı bağlantısı ($db) burada olmalı

// 1. KATEGORİ EKLEME MANTIĞI
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))); // Basit slug
    
    if(!empty($name)){
        $ekle = $db->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $ekle->execute([$name, $slug]);
        header("Location: kategoriler.php?durum=ok");
        exit;
    }
}

// 2. KATEGORİ SİLME MANTIĞI
if (isset($_GET['sil'])) {
    $id = intval($_GET['sil']);
    $db->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    header("Location: kategoriler.php?durum=silindi");
    exit;
}

// 3. VERİYİ SQL'DEN ÇEKME (Senin eksik olan yer burasıydı)
$kategoriler = $db->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-5xl">
    <header class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight text-center md:text-left">Kategoriler</h1>
        <p class="text-slate-500 text-center md:text-left font-medium">Blog yazılarını kategorize et, düzenli kalsın.</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <form action="" method="POST" class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-4 sticky top-24">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-widest">Yeni Kategori Ekle</h3>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400">Kategori Adı</label>
                    <input type="text" name="name" required placeholder="Örn: Yazılım"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition font-semibold">
                </div>
                <button type="submit" name="add_category" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 active:scale-95">
                    Ekle
                </button>
            </form>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Adı</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if(count($kategoriler) > 0): ?>
                                <?php foreach($kategoriler as $k): ?>
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4 font-bold text-slate-900"><?php echo htmlspecialchars($k['name']); ?></td>
                                    <td class="px-6 py-4 text-slate-400 text-sm italic">/<?php echo $k['slug']; ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="?sil=<?php echo $k['id']; ?>" onclick="return confirm('Kategoriyi siliyorsun kanka, emin misin?')" class="text-red-500 text-xs font-bold hover:bg-red-50 px-3 py-1 rounded-full transition opacity-0 group-hover:opacity-100 uppercase">
                                            Sil
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic">Henüz kategori eklenmemiş kanka.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</main> <script>
    // Header'da verdiğim hamburger menü kodunu buraya da ekliyorum (Eğer header.php'de script yoksa)
    const toggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if(toggle){
        toggle.onclick = () => {
            sidebar.classList.toggle('sidebar-closed');
            sidebar.classList.toggle('sidebar-open');
            overlay.classList.toggle('hidden');
        }
    }
</script>
</body>
</html>