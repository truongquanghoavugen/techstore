<?php
$pageTitle = 'Chi tiết tin tức';
require __DIR__ . '/app/partials/header.php';
$articles = [
    1 => ['title' => 'Cách chọn laptop phù hợp cho công việc và học tập', 'date' => '15/09/2026', 'category' => 'Tư vấn', 'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1200', 'intro' => 'Một chiếc laptop phù hợp giúp bạn học tập, làm việc hiệu quả và sử dụng thoải mái trong nhiều năm.', 'sections' => ['Xác định nhu cầu sử dụng trước khi mua', 'Ưu tiên RAM từ 16GB nếu thường xuyên làm việc đa nhiệm hoặc sử dụng phần mềm chuyên môn.', 'Kiểm tra màn hình, bàn phím và thời lượng pin vì đây là những yếu tố ảnh hưởng trực tiếp đến trải nghiệm mỗi ngày.']],
    2 => ['title' => '5 phụ kiện giúp góc máy gọn gàng và hiệu quả hơn', 'date' => '10/09/2026', 'category' => 'Góc công nghệ', 'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=1200', 'intro' => 'Một vài món phụ kiện đúng nhu cầu có thể biến góc máy thành không gian làm việc thoải mái hơn.', 'sections' => ['Đế laptop giúp nâng màn hình lên tầm mắt và cải thiện luồng gió.', 'Bàn phím và chuột không dây giúp mặt bàn gọn hơn.', 'Hub USB-C, giá đỡ tai nghe và đèn bàn là những nâng cấp nhỏ nhưng hữu ích.']],
    3 => ['title' => 'Bảo quản thiết bị công nghệ đúng cách', 'date' => '05/09/2026', 'category' => 'Mẹo hay', 'image' => 'https://images.unsplash.com/photo-1601524909162-ae8725290836?w=1200', 'intro' => 'Thiết bị bền hơn khi được vệ sinh, sạc và bảo quản đúng cách.', 'sections' => ['Không đặt laptop trên bề mặt mềm làm cản khe tản nhiệt.', 'Dùng khăn mềm để vệ sinh màn hình và tránh dung dịch xịt trực tiếp lên thiết bị.', 'Sao lưu dữ liệu quan trọng định kỳ để hạn chế rủi ro khi thiết bị gặp sự cố.']],
    4 => ['title' => 'GearZone cập nhật chính sách bảo hành', 'date' => '01/09/2026', 'category' => 'Thông báo', 'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1200', 'intro' => 'GearZone luôn cố gắng mang đến quy trình hỗ trợ rõ ràng và thuận tiện cho khách hàng.', 'sections' => ['Sản phẩm được tiếp nhận bảo hành theo chính sách của từng thương hiệu.', 'Khách hàng nên giữ hóa đơn và kiểm tra tình trạng sản phẩm trước khi gửi bảo hành.', 'Liên hệ trang Liên hệ để được hướng dẫn kiểm tra thông tin bảo hành.']],
];
$article = $articles[(int) ($_GET['id'] ?? 1)] ?? $articles[1];
?>
<main class="page-main container article-page">
    <a href="/tin-tuc" class="text-link"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Quay lại danh sách tin</a>
    <article class="article-detail">
        <p class="eyebrow"><?= htmlspecialchars($article['category']) ?> · <?= htmlspecialchars($article['date']) ?></p>
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <p class="article-lead"><?= htmlspecialchars($article['intro']) ?></p>
        <div class="article-cover"><img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>"></div>
        <?php foreach ($article['sections'] as $section): ?>
            <p><?= htmlspecialchars($section) ?></p>
        <?php endforeach; ?>
        <p>Hy vọng những thông tin trên giúp bạn có thêm lựa chọn phù hợp. Theo dõi GearZone để cập nhật các bài viết mới.</p>
    </article>
</main>
<?php require __DIR__ . '/app/partials/footer.php'; ?>
