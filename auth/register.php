<?php
$pageTitle = 'Đăng ký';
require __DIR__ . '/partials/header.php';
?>
<main class="auth-page">
    <div class="auth-shell auth-card-wide">
        <section class="auth-card">
            <div class="auth-header">
                <a class="auth-logo" href="index.php">GearZone</a>
                <h1>Tạo tài khoản</h1>
                <p>Đăng ký để quản lý tài khoản và trải nghiệm mua sắm thuận tiện hơn.</p>
            </div>

            <div id="registerAlert" class="auth-alert" role="alert"></div>

            <form id="registerForm" class="auth-form" novalidate>
                <div class="auth-field">
                    <label for="fullName">Họ và tên *</label>
                    <input class="auth-input" id="fullName" name="fullName" type="text"
                           autocomplete="name" placeholder="Nguyễn Văn A" required>
                    <small class="auth-error" data-error-for="fullName"></small>
                </div>

                <div class="auth-field">
                    <label for="registerEmail">Email *</label>
                    <input class="auth-input" id="registerEmail" name="email" type="email"
                           autocomplete="email" placeholder="example@gmail.com" required>
                    <small class="auth-error" data-error-for="email"></small>
                </div>

                <div class="auth-field">
                    <label for="phone">Số điện thoại</label>
                    <input class="auth-input" id="phone" name="phone" type="tel"
                           autocomplete="tel" inputmode="numeric" placeholder="0901234567">
                    <small class="auth-error" data-error-for="phone"></small>
                </div>

                <div class="auth-field">
                    <label for="username">Tên đăng nhập *</label>
                    <input class="auth-input" id="username" name="username" type="text"
                           autocomplete="username" placeholder="gearzone_user" required>
                    <small class="auth-help">3–30 ký tự, chỉ gồm chữ, số, dấu chấm, gạch dưới và gạch ngang.</small>
                    <small class="auth-error" data-error-for="username"></small>
                </div>

                <div class="auth-field">
                    <label for="registerPassword">Mật khẩu *</label>
                    <div class="auth-input-wrap">
                        <input class="auth-input" id="registerPassword" name="password" type="password"
                               autocomplete="new-password" placeholder="Tối thiểu 8 ký tự" required>
                        <button class="auth-toggle-password" type="button" data-toggle-password="registerPassword"
                                aria-label="Hiển thị mật khẩu"><i class="fa-regular fa-eye"></i></button>
                    </div>
                    <div class="auth-password-meter">
                        <div class="auth-password-bar"><span id="passwordBar"></span></div>
                        <div class="auth-password-text" id="passwordText">Chưa nhập mật khẩu</div>
                    </div>
                    <small class="auth-error" data-error-for="password"></small>
                </div>

                <div class="auth-field">
                    <label for="confirmPassword">Xác nhận mật khẩu *</label>
                    <div class="auth-input-wrap">
                        <input class="auth-input" id="confirmPassword" name="confirmPassword" type="password"
                               autocomplete="new-password" placeholder="Nhập lại mật khẩu" required>
                        <button class="auth-toggle-password" type="button" data-toggle-password="confirmPassword"
                                aria-label="Hiển thị mật khẩu"><i class="fa-regular fa-eye"></i></button>
                    </div>
                    <small class="auth-error" data-error-for="confirmPassword"></small>
                </div>

                <label class="auth-checkbox">
                    <input id="terms" name="terms" type="checkbox" required>
                    <span>Tôi đồng ý với <a href="#" onclick="return false;">Điều khoản sử dụng</a> và chính sách bảo mật của GearZone.</span>
                </label>
                <small class="auth-error" data-error-for="terms"></small>

                <button class="auth-submit" type="submit">Đăng ký tài khoản</button>
            </form>

            <div id="registerSuccess" class="auth-success-box">
                <div class="auth-success-icon"><i class="fa-solid fa-check"></i></div>
                <h2>Đăng ký thành công</h2>
                <p>Tài khoản của bạn đã được tạo ở chế độ frontend demo.</p>
                <a class="button" href="login.php">Đi đến đăng nhập</a>
            </div>

            <div class="auth-links auth-links-center">
                <span>Đã có tài khoản?</span>
                <a href="login.php">Đăng nhập</a>
            </div>

            <div class="auth-demo-note">
                <strong>Chế độ frontend:</strong> dữ liệu tài khoản demo được lưu trong trình duyệt để kiểm thử giao diện.
                Khi nhóm có API/Database, thay phần lưu demo trong <code>assets/auth.js</code> bằng API thật.
            </div>
        </section>
    </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
