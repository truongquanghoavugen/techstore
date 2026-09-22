<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/img/image.ico">

    <title>Đăng nhập</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/auth.css">
</head>

<body>

<a class="auth-home-link" href="/trang-chu"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Về trang chủ GearZone</a>

<div class="auth-container">

    <div class="auth-box">

        <div class="auth-header">
            <h1>Đăng nhập</h1>
            <p>Đăng nhập vào tài khoản của bạn</p>
        </div>

        <form id="loginForm">

            <div class="form-group">

                <label for="loginEmail">
                    Email
                </label>

                <input
                    type="email"
                    id="loginEmail"
                    name="email"
                    placeholder="Nhập email"
                    autocomplete="email"
                >

                <small
                    class="error"
                    id="loginEmailError">
                </small>

            </div>


            <div class="form-group">

                <label for="loginPassword">
                    Mật khẩu
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="loginPassword"
                        name="password"
                        placeholder="Nhập mật khẩu"
                        autocomplete="current-password"
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword('loginPassword', this)">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    </button>

                </div>

                <small
                    class="error"
                    id="loginPasswordError">
                </small>

            </div>


            <div class="forgot-link">

                <a href="/quen-mat-khau">
                    Quên mật khẩu?
                </a>

            </div>


            <button
                type="submit"
                class="auth-button">
                Đăng nhập
            </button>


            <div
                id="loginMessage"
                class="message">
            </div>

        </form>


        <div class="switch-page">

            <span>Chưa có tài khoản?</span>

            <a href="/dang-ky">
                Đăng ký
            </a>

        </div>

    </div>

</div>


<script src="/assets/auth.js?v=20260916"></script>

</body>
</html>