<?php 
include 'header.php'; // Veritabanı ve Sidebar burada

$id = intval($_GET['id']);
if(!$id) { header("Location: yazilar.php"); exit; }

// Mevcut yazıyı çek
$sorgu = $db->prepare("SELECT * FROM posts WHERE id = ?");
$sorgu->execute([$id]);
$yazi = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$yazi) { echo "Yazı bulunamadı!"; exit; }

if ($_POST) {
    $title = secure($_POST['title']);
    $content = $_POST['content']; // Editörden gelecek zengin içerik
    $meta_desc = secure($_POST['meta_desc']);
    $slug = seolink($title);
    $thumbnail = $yazi['thumbnail']; // Varsayılan eski görsel

    // Yeni görsel yüklendiyse eskisini değiştir
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $upload_dir = "../uploads/";
        $file_name = $slug . "-" . time() . "." . pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $file_name)) {
            $thumbnail = $file_name;
        }
    }

    $update = $db->prepare("UPDATE posts SET title=?, slug=?, content=?, meta_desc=?, thumbnail=? WHERE id=?");
    $ok = $update->execute([$title, $slug, $content, $meta_desc, $thumbnail, $id]);

    if ($ok) { 
        echo "<script>alert('Değişiklikler kaydedildi!'); window.location='yazilar.php';</script>"; 
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
<style>
    /* Admin Paneli Tasarımına Uyum */
    .sun-editor { border-radius: 1.5rem !important; border: 1px solid #e2e8f0 !important; font-family: inherit !important; overflow: hidden; }
    .sun-editor .se-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    .sun-editor .se-btn-module-border { border: 1px solid #f1f5f9 !important; }
</style>

<div class="max-w-4xl mx-auto px-4 md:px-0 pb-20">
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">İçeriği Düzenle</h1>
            <p class="text-slate-500 italic text-sm">Düzenlenen: <?php echo $yazi['title']; ?></p>
        </div>
        <a href="yazilar.php" class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition">← Listeye Dön</a>
    </header>

    <form action="" method="POST" enctype="multipart/form-data" id="editForm" class="space-y-8 bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Makale Başlığı</label>
                <input type="text" name="title" value="<?php echo $yazi['title']; ?>" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Öne Çıkan Görsel (Değiştirmeceksen boş bırak)</label>
                <div class="flex items-center gap-4">
                    <img src="../uploads/<?php echo $yazi['thumbnail']; ?>" class="w-12 h-12 rounded-lg object-cover bg-slate-100">
                    <input type="file" name="thumbnail" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700">
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-slate-700">SEO Açıklaması</label>
            <textarea name="meta_desc" rows="2" maxlength="160"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition"><?php echo $yazi['meta_desc']; ?></textarea>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-slate-700">Makale İçeriği</label>
            <textarea id="editor_area" name="content"><?php echo $yazi['content']; ?></textarea>
        </div>

        <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-12 py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 active:scale-95 transform">
            Güncellemeleri Kaydet
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/src/lang/tr.js"></script>

<script>
    const editor = SUNEDITOR.create('editor_area', {
        display: 'block',
        width: '100%',
        height: 'auto',
        minHeight: '400px',
        placeholder: 'İçeriği güncelle kanka...',
        buttonList: [
            ['undo', 'redo'],
            ['font', 'fontSize', 'formatBlock'],
            ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
            ['fontColor', 'hiliteColor', 'textStyle'],
            ['removeFormat'],
            ['outdent', 'indent'],
            ['align', 'horizontalRule', 'list', 'lineHeight'],
            ['table', 'link', 'image', 'video'],
            ['fullScreen', 'showBlocks', 'codeView'],
            ['preview', 'print']
        ],
        lang: SUNEDITOR_LANG['tr']
    });

    // Form gönderilirken içeriği aktar
    document.getElementById('editForm').onsubmit = function() {
        document.getElementById('editor_area').value = editor.getContents();
    };
</script>

</main></body></html>