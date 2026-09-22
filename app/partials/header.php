<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'GearZone';
$page = basename($_SERVER['PHP_SELF']);
$accountLink = isset($_SESSION['user_id']) ? '/tai-khoan' : '/dang-nhap';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/img/image.ico">
    <title><?= htmlspecialchars($pageTitle) ?> | GearZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/style.css">
    <script src="/assets/app.js?v=20260916"></script>
    <?php if (!empty($extraStylesheet)): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($extraStylesheet) ?>">
    <?php endif; ?>
</head>
<body data-authenticated="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>">
<div id="site-toast" class="site-toast" role="status" aria-live="polite"></div>
<header class="site-header">
    <div class="header-inner">
        <nav class="main-nav">
            <a class="<?= $page === 'index.php' ? 'active' : '' ?>" href="/trang-chu">Trang chủ</a>
            <a class="has-arrow <?= $page === 'products.php' ? 'active' : '' ?>" href="/san-pham">Sản phẩm</a>
            <a class="<?= in_array($page, ['news.php', 'new-details.php'], true) ? 'active' : '' ?>" href="/tin-tuc">Tin tức</a>
            <a class="<?= $page === 'contact.php' ? 'active' : '' ?>" href="/lien-he">Liên hệ</a>
            <a class="about-link <?= $page === 'about.php' ? 'active' : '' ?>" href="/gioi-thieu">Giới thiệu</a>
        </nav>
        <a class="logo" href="/trang-chu"><span>GearZone</span><strong></strong></a>
        <div class="header-actions">
            <strong class="hotline">HOTLINE: <a href="tel:0000">0000</a></strong>
            <a href="<?= htmlspecialchars($accountLink) ?>" aria-label="Tài khoản" title="Tài khoản"><i class="fa-solid fa-user" aria-hidden="true"></i></a>
            <a href="/san-pham" aria-label="Tìm kiếm" title="Tìm kiếm"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></a>
            <a class="cart-link" href="/gio-hang" aria-label="Giỏ hàng" title="Giỏ hàng"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i><span class="cart-count">0</span></a>
        </div>
    </div>
</header>
