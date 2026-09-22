<?php

session_start();

if (
    !isset($_SESSION["admin_id"]) ||
    !isset($_SESSION["admin_role"]) ||
    $_SESSION["admin_role"] !== "admin"
) {
    header("Location: ../auth/login.php");
    exit;
}

$adminId = $_SESSION["admin_id"];
$adminName = $_SESSION["admin_name"] ?? "Admin";
$adminEmail = $_SESSION["admin_email"] ?? "";
$adminRole = $_SESSION["admin_role"];

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <meta charset="UTF-8">

    <link rel="icon" type="image/x-icon" href="/img/image.ico">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard</title>

    <link
        rel="stylesheet"
        href="/assets/admin.css"
    >

</head>

<body>

<div class="admin-container">


    <header class="admin-header">

        <div class="admin-title">

            <h1>Admin Dashboard</h1>

            <p>
                Trang quản trị hệ thống
            </p>

        </div>

        <a class="home-btn" href="/trang-chu">
            <i class="fa-solid fa-house" aria-hidden="true"></i> Về trang chủ GearZone
        </a>

        <button
            id="logoutBtn"
            class="logout-btn"
        >
            Đăng xuất
        </button>

    </header>



    <main class="admin-main">
        <section class="welcome-card">
            <div class="welcome-icon">
                <i class="fa-solid fa-hand" aria-hidden="true"></i>
            </div>
            <div>
                <h2>
                    Xin chào,
                    <?php echo htmlspecialchars($adminName); ?>!
                </h2>
                <p>
                    Bạn đã đăng nhập thành công
                    với quyền quản trị viên.
                </p>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">
                <i class="fa-solid fa-user-shield" aria-hidden="true"></i> Thông tin Admin
            </h2>
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">ID</span>
                    <span><?php echo htmlspecialchars($adminId); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Họ tên</span>
                    <span><?php echo htmlspecialchars($adminName); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span><?php echo htmlspecialchars($adminEmail); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Quyền</span>
                    <span class="role-badge"><?php echo htmlspecialchars($adminRole); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phiên đăng nhập</span>
                    <span class="session-active">
                        <i class="fa-solid fa-circle" aria-hidden="true"></i> Đang hoạt động
                    </span>
                </div>
            </div>
        </section>

        <section class="section management-section">
            <div class="management-heading">
                <div>
                    <p class="section-kicker">Quản trị dữ liệu</p>
                    <h2 class="section-title">Quản lý cửa hàng</h2>
                </div>
                <div class="management-tabs" role="tablist" aria-label="Các khu vực quản lý">
                    <button class="management-tab active" type="button" data-admin-tab="products">
                        <i class="fa-solid fa-box-open" aria-hidden="true"></i> Sản phẩm
                    </button>
                    <button class="management-tab" type="button" data-admin-tab="categories">
                        <i class="fa-solid fa-tags" aria-hidden="true"></i> Danh mục
                    </button>
                    <button class="management-tab" type="button" data-admin-tab="users">
                        <i class="fa-solid fa-users" aria-hidden="true"></i> Tài khoản
                    </button>
                    <button class="management-tab" type="button" data-admin-tab="orders">
                        <i class="fa-solid fa-receipt" aria-hidden="true"></i> Đơn hàng
                    </button>
                    <button class="management-tab" type="button" data-admin-tab="contacts">
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i> Liên hệ
                    </button>
                </div>
            </div>

            <div class="management-panel active" data-admin-panel="products">
                <div class="panel-toolbar">
                    <div>
                        <h3>Danh sách sản phẩm</h3>
                        <p>Thêm, chỉnh sửa giá, tồn kho và thông tin sản phẩm.</p>
                    </div>
                    <button class="primary-admin-button" type="button" id="newProductBtn">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Thêm sản phẩm
                    </button>
                </div>
                <div class="product-search-row">
                    <label class="product-search-box" for="productSearchInput">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input id="productSearchInput" type="search" placeholder="Tìm kiếm sản phẩm, danh mục, mô tả..." aria-label="Tìm kiếm sản phẩm">
                    </label>
                    <span id="productSearchResultCount" class="search-result-count">0 sản phẩm</span>
                </div>
                <form class="admin-form hidden" id="productForm">
                    <input type="hidden" id="productId">
                    <div class="admin-form-grid">
                        <label>Tên sản phẩm<input id="productName" required></label>
                        <label>Danh mục<input id="productCategory" list="categoryOptions" required><datalist id="categoryOptions"></datalist></label>
                        <label>Giá bán<input id="productPrice" type="number" min="0" step="1000" required></label>
                        <label>Tồn kho<input id="productStock" type="number" min="0" required></label>
                        <label class="wide-field">Hình ảnh<input id="productImage" type="url" placeholder="https://..."></label>
                        <label class="wide-field">Mô tả<textarea id="productDescription" rows="3"></textarea></label>
                    </div>
                    <div class="form-actions">
                        <button class="primary-admin-button" type="submit"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Lưu sản phẩm</button>
                        <button class="secondary-admin-button" type="button" id="cancelProductBtn">Hủy</button>
                    </div>
                </form>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Tồn kho</th><th>Thao tác</th></tr></thead>
                        <tbody id="productsTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="management-panel" data-admin-panel="categories">
                <div class="panel-toolbar">
                    <div>
                        <h3>Danh mục sản phẩm</h3>
                        <p>Đổi tên hoặc xóa danh mục đang dùng trong cửa hàng.</p>
                    </div>
                </div>
                <form class="inline-admin-form" id="categoryForm">
                    <input type="hidden" id="categoryId">
                    <input id="categoryName" placeholder="Tên danh mục mới" required>
                    <button class="primary-admin-button" type="submit"><i class="fa-solid fa-plus" aria-hidden="true"></i> Lưu danh mục</button>
                    <button class="secondary-admin-button hidden" type="button" id="cancelCategoryBtn">Hủy sửa</button>
                </form>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Tên danh mục</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
                        <tbody id="categoriesTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="management-panel" data-admin-panel="users">
                <div class="panel-toolbar">
                    <div>
                        <h3>Tài khoản người dùng</h3>
                        <p>Cập nhật tên, số điện thoại, quyền hoặc xóa tài khoản.</p>
                    </div>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Tài khoản</th><th>Email</th><th>Số điện thoại</th><th>Quyền</th><th>Thao tác</th></tr></thead>
                        <tbody id="usersTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="management-panel" data-admin-panel="orders">
                <div class="panel-toolbar">
                    <div>
                        <h3>Đơn hàng khách hàng</h3>
                        <p>Theo dõi các đơn hàng mới và trạng thái xử lý.</p>
                    </div>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Email</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th><th>Thao tác</th></tr></thead>
                        <tbody id="ordersTableBody"></tbody>
                    </table>
                </div>
            </div>

            <div class="management-panel" data-admin-panel="contacts">
                <div class="panel-toolbar">
                    <div>
                        <h3>Tin nhắn liên hệ</h3>
                        <p>Thông tin khách hàng gửi từ biểu mẫu liên hệ.</p>
                    </div>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Khách hàng</th><th>Liên hệ</th><th>Chủ đề</th><th>Nội dung</th><th>Tệp đính kèm</th><th>Ngày gửi</th></tr></thead>
                        <tbody id="contactsTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="admin-inline-message" id="adminManagementMessage" role="status"></div>
        </section>

    </main>


    <footer class="admin-footer">

        <p>
            Webshop Admin System
        </p>

    </footer>

</div>

<div class="logout-modal hidden" id="adminLogoutModal" role="dialog" aria-modal="true" aria-labelledby="adminLogoutModalTitle">
    <div class="logout-modal-backdrop" data-close-admin-logout></div>
    <div class="logout-modal-card">
        <div class="logout-modal-icon"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i></div>
        <h2 id="adminLogoutModalTitle">Đăng xuất Admin?</h2>
        <p>Bạn có chắc muốn kết thúc phiên quản trị này không?</p>
        <div class="logout-modal-actions">
            <button class="secondary-admin-button" type="button" data-close-admin-logout>Hủy</button>
            <button class="primary-admin-button" type="button" id="confirmAdminLogoutBtn">Đăng xuất</button>
        </div>
    </div>
</div>

<div class="logout-modal hidden" id="confirmDeleteModal" role="dialog" aria-modal="true" aria-labelledby="confirmDeleteModalTitle">
    <div class="logout-modal-backdrop" data-close-delete-confirm></div>
    <div class="logout-modal-card">
        <div class="logout-modal-icon danger"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></div>
        <h2 id="confirmDeleteModalTitle">Xác nhận xóa</h2>
        <p id="confirmDeleteMessage">Bạn có chắc muốn xóa mục này không?</p>
        <div class="logout-modal-actions">
            <button class="secondary-admin-button" type="button" data-close-delete-confirm>Hủy</button>
            <button class="primary-admin-button danger-button" type="button" id="confirmDeleteBtn">Xóa</button>
        </div>
    </div>
</div>


<script src="/assets/admin.js"></script>

</body>

</html>