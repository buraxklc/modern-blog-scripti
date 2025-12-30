<?php 
include 'header.php'; 

// Mevcut ayarları çek (settings tablosunda 1 id'li satır olduğunu varsayıyoruz)
$settings = $db->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $title = secure($_POST['site_title']);
    $desc = secure($_POST['site_desc']);
    $footer = secure($_POST['site_footer']);
    $instagram = secure($_POST['social_instagram']);

    // Logo Güncelleme
    $logo = $settings['site_logo'];
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $name = "logo-" . time() . ".png";
        move_uploaded_file($_FILES['logo']['tmp_name'], "../assets/" . $name);
        $logo = $name;
    }

    $update = $db->prepare("UPDATE settings SET site_title=?, site_desc=?, site_footer=?, social_instagram=?, site_logo=? WHERE id=1");
    $update->execute([$title, $desc, $footer, $instagram, $logo]);
    echo "<script>alert('Ayarlar güncellendi!'); window.location='ayarlar.php';</script>";
}
?>

<div class="max-w-4xl mx-auto px-4 md:px-0 pb-20">
    <header class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Genel Ayarlar</h1>
        <p class="text-slate-500 text-sm">Site kimliğini ve sosyal medya linklerini buradan yönet.</p>
    </header>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-6 md:p-10 rounded-3xl border border-slate-100 shadow-sm">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Site Başlığı</label>
                <input type="text" name="site_title" value="<?php echo $settings['site_title']; ?>"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Site Logosu</label>
                <div class="flex items-center gap-4">
                    <img src="../assets/<?php echo $settings['site_logo']; ?>" class="h-10 w-auto">
                    <input type="file" name="logo" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700">
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-slate-700">SEO Açıklaması</label>
            <textarea name="site_desc" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition"><?php echo $settings['site_desc']; ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Instagram URL</label>
                <input type="text" name="social_instagram" value="<?php echo $settings['social_instagram']; ?>" placeholder="https://instagram.com/..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Footer Yazısı</label>
                <input type="text" name="site_footer" value="<?php echo $settings['site_footer']; ?>"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition">
            </div>
        </div>

        <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-12 py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
            Ayarları Kaydet
        </button>
    </form>
</div>