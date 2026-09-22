<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

unset($_SESSION["admin_id"]);
unset($_SESSION["admin_name"]);
unset($_SESSION["admin_email"]);
unset($_SESSION["admin_role"]);

echo json_encode([
    "success" => true,
    "message" => "Đăng xuất Admin thành công."
]);

exit;
?>