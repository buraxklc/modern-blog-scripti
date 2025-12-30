<?php
// 1. Oturum Başlatma (Admin paneli girişleri için şart)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * SEO Dostu URL (Slug) Oluşturucu
 * Örn: "PHP ve Tailwind ile Blog Yapımı!" -> "php-ve-tailwind-ile-blog-yapimi"
 */
function seolink($string) {
    $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#', '.', ',', '!', '?', ':', ';');
    $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp', '', '', '', '', '', '');
    $string = strtolower(str_replace($find, $replace, $string));
    $string = preg_replace("@[^A-Za-z0-9\-_\.\s]@i", ' ', $string);
    $string = trim(preg_replace('/\s+/', ' ', $string));
    $string = str_replace(' ', '-', $string);
    return $string;
}

/**
 * Güvenlik Filtresi (XSS Engelleme)
 * Formdan gelen verileri temizlemek için kullanılır.
 */
function secure($data) {
    if (is_array($data)) {
        return array_map('secure', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Admin Yetki Kontrolü
 * Yetkisiz girişleri login sayfasına postalar.
 */
function adminCheck() {
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_login'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Türkçe Tarih Formatı
 * 2025-12-30 -> 30 Aralık 2025 yapar.
 */
function timeTR($date) {
    $months = array(
        'January' => 'Ocak', 'February' => 'Şubat', 'March' => 'Mart',
        'April' => 'Nisan', 'May' => 'Mayıs', 'June' => 'Haziran',
        'July' => 'Temmuz', 'August' => 'Ağustos', 'September' => 'Eylül',
        'October' => 'Ekim', 'November' => 'Kasım', 'December' => 'Aralık'
    );
    $en_date = date('d F Y', strtotime($date));
    return strtr($en_date, $months);
}

/**
 * Metin Kısaltıcı (Özet Oluşturucu)
 * Uzun içerikleri belirli bir karakterde kesip "..." ekler.
 */
function shortenText($text, $limit = 160) {
    $text = strip_tags($text); // HTML etiketlerini temizle
    if (mb_strlen($text) > $limit) {
        $text = mb_substr($text, 0, $limit) . "...";
    }
    return $text;
}

/**
 * Alert Mesajları
 * İşlem sonuçlarını (Başarılı/Hatalı) ekrana basar.
 */
function showAlert($type, $message) {
    $color = ($type == 'success') ? 'green' : 'red';
    return '<div class="p-4 mb-4 text-sm font-bold border-2 border-'.$color.'-500 bg-'.$color.'-50 text-'.$color.'-700 rounded-lg">
                '.$message.'
            </div>';
}

/**
 * SEO İçin Dinamik Title Oluşturucu
 */
function getTitle($title = "") {
    $siteName = "DevBlog"; // Burayı site adınla değiştir
    return empty($title) ? $siteName : $title . " | " . $siteName;
}
?>