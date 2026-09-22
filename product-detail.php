<?php
$pageTitle = 'Chi tiết sản phẩm';
require __DIR__ . '/app/config/db.php';
$productId = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT id, name, description, price, image, category, stock FROM products WHERE id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();
require __DIR__ . '/app/partials/header.php';
?>
<link rel="stylesheet" href="/sanpham.css">
<?php if (!$product): ?>
<main class="page-main container"><h1>Không tìm thấy sản phẩm</h1><p><a class="text-link" href="products.php">Quay lại danh sách sản phẩm</a></p></main>
<?php else: ?>
<main class="detail-container">
    <div class="detail-top">
        <div class="gallery-section">
            <div class="main-img-box">
                <?php if (!empty($product['image'])): ?><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"><?php else: ?>Hình ảnh sản phẩm<?php endif; ?>
            </div>
        </div>
        <div class="info-section">
            <p class="product-category"><?= htmlspecialchars($product['category'] ?? 'Công nghệ') ?></p>
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="product-meta">
                <span>Mã SP: <strong>SP-<?= (int) $product['id'] ?></strong></span>
                <span>Tình trạng: <strong><?= (int) $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng' ?></strong></span>
            </div>
            <div class="price-box-detail"><span class="detail-current-price"><?= number_format((float) $product['price'], 0, ',', '.') ?>đ</span></div>
            <p class="short-desc"><?= htmlspecialchars($product['description'] ?? 'Sản phẩm chính hãng, chất lượng cao.') ?></p>
            <div class="quantity-control">
                <label for="detail-qty"><strong>Số lượng:</strong></label>
                <button class="qty-btn" type="button" data-step="-1">-</button>
                <input id="detail-qty" class="qty-input" type="number" value="1" min="1" max="<?= (int) $product['stock'] ?>" readonly>
                <button class="qty-btn" type="button" data-step="1">+</button>
            </div>
            <div class="detail-actions">
                <button class="btn-add-detail" type="button" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>Thêm vào giỏ</button>
                <a class="btn-buy-now" href="cart.php">Mua ngay</a>
            </div>
        </div>
    </div>
    <div class="detail-bottom">
        <h2>Mô tả sản phẩm</h2>
        <p><?= htmlspecialchars($product['description'] ?? 'Đang cập nhật mô tả sản phẩm.') ?></p>
        <h2>Thông số cơ bản</h2>
        <table class="specs-table">
            <tr><td>Danh mục</td><td><?= htmlspecialchars($product['category'] ?? 'Công nghệ') ?></td></tr>
            <tr><td>Mã sản phẩm</td><td>SP-<?= (int) $product['id'] ?></td></tr>
            <tr><td>Tồn kho</td><td><?= (int) $product['stock'] ?> sản phẩm</td></tr>
        </table>
    </div>
</main>
<script>
const product = <?= json_encode(['id' => (int) $product['id'], 'name' => $product['name'], 'price' => (float) $product['price'], 'stock' => (int) $product['stock'], 'img' => $product['image'] ?? ''], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
function showToast(message) {
    const toast = document.getElementById('site-toast');
    toast.textContent = message;
    toast.classList.add('is-visible');
    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => toast.classList.remove('is-visible'), 2800);
}

const quantity = document.querySelector('#detail-qty');
document.querySelectorAll('.qty-btn').forEach(button => button.addEventListener('click', () => {
    quantity.value = Math.max(1, Math.min(product.stock, Number(quantity.value) + Number(button.dataset.step)));
}));
document.querySelector('.btn-add-detail')?.addEventListener('click', () => {
    if (!window.requireAuthenticated?.()) return;
    const cart = JSON.parse(localStorage.getItem('gearzoneCart') || '[]');
    const existing = cart.find(item => item.id === product.id);
    if (existing) existing.qty = Math.min(existing.qty + Number(quantity.value), product.stock);
    else cart.push({ ...product, qty: Number(quantity.value) });
    localStorage.setItem('gearzoneCart', JSON.stringify(cart));
    window.updateCartBadge?.();
    showToast('Đã thêm sản phẩm vào giỏ hàng.');
});
</script>
<?php endif; ?>
<?php require __DIR__ . '/app/partials/footer.php'; ?>
