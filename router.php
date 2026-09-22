<?php

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = '/' . trim($uri, '/');

$routes = [
    '/' => __DIR__ . '/index.php',
    '/trang-chu' => __DIR__ . '/index.php',
    '/san-pham' => __DIR__ . '/products.php',
    '/tin-tuc' => __DIR__ . '/news.php',
    '/gioi-thieu' => __DIR__ . '/about.php',
    '/lien-he' => __DIR__ . '/contact.php',
    '/gio-hang' => __DIR__ . '/cart.php',
    '/thanh-toan' => __DIR__ . '/checkout.php',
    '/tai-khoan' => __DIR__ . '/app/account/account.php',
    '/dang-nhap' => __DIR__ . '/app/auth/login.php',
    '/dang-ky' => __DIR__ . '/app/auth/register.php',
    '/quen-mat-khau' => __DIR__ . '/app/auth/forgot-password.php',
];

if (isset($routes[$uri])) {
    include $routes[$uri];
    exit;
}

$internalUri = $uri;
if ($uri === "/img/image.ico") {
    $path = __DIR__ . "/img/image.ico";
    header("Content-Type: image/x-icon");
    readfile($path);
    exit;
}

foreach (["/account", "/admin", "/api", "/auth", "/config", "/partials"] as $prefix) {
    if ($uri === $prefix || str_starts_with($uri, $prefix . "/")) {
        $internalUri = "/app" . $uri;
        break;
    }
}

$path = __DIR__ . $internalUri;
if (is_file($path)) {
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $mimeTypes = [
        "css" => "text/css; charset=UTF-8",
        "js" => "application/javascript; charset=UTF-8",
        "png" => "image/png",
        "jpg" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "svg" => "image/svg+xml",
        "webp" => "image/webp",
        "ico" => "image/x-icon"
    ];
    if (isset($mimeTypes[$extension])) {
        header("Content-Type: " . $mimeTypes[$extension]);
        readfile($path);
        exit;
    }
    include $path;
    exit;
}

http_response_code(404);
echo '404 - Không tìm thấy trang';