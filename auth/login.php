<?php
$pageTitle = 'Đăng nhập';
require __DIR__ . '/partials/header.php';
?>
<main class="auth-page">
    <div class="auth-shell">
        <section class="auth-card">
            <div class="auth-header">
                <a class="auth-logo" href="index.php">GearZone</a>
                <h1>Đăng nhập</h1>
                <p>Đăng nhập để tiếp tục với tài khoản GearZone.</p>
            </div>

            <div id="loginAlert" class="auth-alert" role="alert"></div>

            <form id="loginForm" class="auth-form" novalidate>
                <div class="auth-field">
                    <label for="loginAccount">Email hoặc tên đăng nhập *</label>
                    <input class="auth-input" id="loginAccount" name="account" type="text"
                           autocomplete="username" placeholder="Email hoặc username" required>
                    <small class="auth-error" data-error-for="account"></small>
                </div>

                <div class="auth-field">
                    <label for="loginPassword">Mật khẩu *</label>
                    <div class="auth-input-wrap">
                        <input class="auth-input" id="loginPassword" name="password" type="password"
                               autocomplete="current-password" placeholder="Nhập mật khẩu" required>
                        <button class="auth-toggle-password" type="button" data-toggle-password="loginPassword"
                                aria-label="Hiển thị mật khẩu"><i class="fa-regular fa-eye"></i></button>
                    </div>
                    <small class="auth-error" data-error-for="password"></small>
                </div>

                <label class="auth-checkbox">
                    <input id="remember" name="remember" type="checkbox">
                    <span>Ghi nhớ đăng nhập trên thiết bị này</span>
                </label>

                <button class="auth-submit" type="submit">Đăng nhập</button>
            </form>

            <div class="auth-links">
                <a href="forgot-password.php">Quên mật khẩu?</a>
                <span>Chưa có tài khoản? <a href="register.php">Đăng ký</a></span>
            </div>

            <div class="auth-demo-note">
                <strong>Trạng thái được hỗ trợ ở frontend:</strong> sai tài khoản, sai mật khẩu, tài khoản bị khóa,
                tài khoản chưa xác thực và đăng nhập thành công. Backend thật cần trả về trạng thái tương ứng.
            </div>
        </section>
    </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
