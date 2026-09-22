<?php
$pageTitle = $pageTitle ?? 'GearZone';
$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | GearZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <nav class="main-nav">
            <a class="<?= $page === 'index.php' ? 'active' : '' ?>" href="index.php">Trang chủ</a>
            <a class="has-arrow <?= $page === 'products.php' ? 'active' : '' ?>" href="products.php">Sản phẩm</a>
            <a class="<?= $page === 'news.php' ? 'active' : '' ?>" href="news.php">Tin tức</a>
            <a class="<?= $page === 'contact.php' ? 'active' : '' ?>" href="contact.php">Liên hệ</a>
            <a class="about-link <?= $page === 'about.php' ? 'active' : '' ?>" href="about.php">Giới thiệu</a>
        </nav>
        <a class="logo" href="index.php"><span>GearZone</span><strong></strong></a>
        <div class="header-actions">
            <strong class="hotline">HOTLINE: <a href="tel:0000">0000</a></strong>
            <a href="contact.php" aria-label="Tài khoản" title="Tài khoản"><i class="fa-solid fa-user" aria-hidden="true"></i></a>
            <a href="products.php" aria-label="Tìm kiếm" title="Tìm kiếm"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></a>
            <a class="cart-link" href="cart.php" aria-label="Giỏ hàng" title="Giỏ hàng"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i><span>0</span></a>
        </div>
    </div>
</header>
