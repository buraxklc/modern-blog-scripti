<?php 
include 'header.php'; // Veritabanı ve Sidebar buraya dahil

// Kategorileri Veritabanından Çekiyoruz
$kategoriler = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $title = secure($_POST['title']);
    $content = $_POST['content']; 
    $meta_desc = secure($_POST['meta_desc']);
    $category_id = intval($_POST['category_id']); // Kategori ID'sini aldık
    $slug = seolink($title);
    $thumbnail = "";

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $upload_dir = "../uploads/";
        $file_name = $slug . "-" . time() . "." . pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $file_name)) {
            $thumbnail = $file_name;
        }
    }

    // Sorguya category_id eklendi
    $sorgu = $db->prepare("INSERT INTO posts (title, slug, category_id, content, meta_desc, thumbnail, status) VALUES (?, ?, ?, ?, ?, ?, 'published')");
    $ekle = $sorgu->execute([$title, $slug, $category_id, $content, $meta_desc, $thumbnail]);

    if ($ekle) { echo "<script>alert('Yazı yayınlandı kanka!'); window.location='yazilar.php';</script>"; }
}
?>

<link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
<style>
    .sun-editor { border-radius: 1.5rem !important; border: 1px solid #e2e8f0 !important; font-family: inherit !important; overflow: hidden; }
    .sun-editor .se-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; outline: none !important; }
    .sun-editor .se-resizing-bar { background-color: #f8fafc !important; }
    .sun-editor .se-btn-module-border { border: 1px solid #f1f5f9 !important; }
    @media (max-width: 768px) { .sun-editor { width: 100% !important; } }
</style>

<div class="max-w-4xl mx-auto px-4 md:px-0 pb-20">
    <header class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Yeni İçerik Oluştur</h1>
        <p class="text-slate-500">Metinleri renklendir, resimleri köşesinden tutup boyutlandır.</p>
    </header>

    <form action="" method="POST" enctype="multipart/form-data" id="postForm" class="space-y-8 bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Makale Başlığı</label>
                <input type="text" name="title" required placeholder="Örn: PHP ile Gelişmiş Blog"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Kategori</label>
                <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition bg-white cursor-pointer">
                    <option value="">Kategori Seçin</option>
                    <?php foreach($kategoriler as $k): ?>
                        <option value="<?php echo $k['id']; ?>"><?php echo $k['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Öne Çıkan Görsel</label>
                <input type="file" name="thumbnail" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">SEO Açıklaması</label>
                <textarea name="meta_desc" rows="1" maxlength="160" placeholder="Kısa özet..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition"></textarea>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-slate-700">Makale İçeriği</label>
            <textarea id="my_editor" name="content"></textarea>
        </div>

        <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-12 py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 active:scale-95 transform">
            Yazıyı Yayınla
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/src/lang/tr.js"></script>

<script>
    const editor = SUNEDITOR.create('my_editor', {
        display: 'block',
        width: '100%',
        height: 'auto',
        minHeight: '400px',
        placeholder: 'Yazmaya başla kanka...',
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

    document.getElementById('postForm').onsubmit = function() {
        document.getElementById('my_editor').value = editor.getContents();
    };
</script>