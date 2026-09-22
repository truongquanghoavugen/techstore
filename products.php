<?php
$pageTitle = 'Sản phẩm';
require __DIR__ . '/partials/header.php';
$products = ['Laptop UltraBook Pro', 'Điện thoại Nova X', 'Tai nghe Sonic Air', 'Bàn phím cơ RGB'];
?>
<main class="page-main container">
    <p class="eyebrow">GearZone Store</p>
    <h1>Sản phẩm công nghệ</h1>
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <article class="product-item"><div class="product-placeholder">⚡</div><h2><?= htmlspecialchars($product) ?></h2><p>Thiết kế hiện đại, hiệu năng ổn định và bảo hành chính hãng.</p><strong>Liên hệ để nhận giá</strong></article>
        <?php endforeach; ?>
    </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
