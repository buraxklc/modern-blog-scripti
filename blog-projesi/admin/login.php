<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';

// Eğer zaten giriş yapmışsa direkt panele yönlendir
if (isset($_SESSION['admin_login'])) {
    header("Location: index.php");
    exit;
}

$hata = "";

if ($_POST) {
    $user = secure($_POST['username']);
    $pass = $_POST['password'];

    if (!empty($user) && !empty($pass)) {
        $sorgu = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $sorgu->execute([$user]);
        $admin = $sorgu->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($pass, $admin['password'])) {
            // Giriş Başarılı
            $_SESSION['admin_login'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            header("Location: index.php");
            exit;
        } else {
            $hata = "Kullanıcı adı veya şifre hatalı!";
        }
    } else {
        $hata = "Lütfen tüm alanları doldurun.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi | DevBlog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full p-8 bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Hoş Geldin Kral 👋</h1>
            <p class="text-slate-500 mt-2">Panele erişmek için giriş yap.</p>
        </div>

        <?php if($hata): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-bold mb-6 border border-red-100">
                <?php echo $hata; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kullanıcı Adı</label>
                <input type="text" name="username" required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Şifre</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition">
            </div>

            <button type="submit" 
                class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 active:scale-[0.98]">
                Giriş Yap
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="../index.php" class="text-sm text-slate-400 hover:text-slate-900 transition">← Siteye Geri Dön</a>
        </div>
    </div>

</body>
</html>