<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email']) || !isset($_POST['phone'])) {
        echo 'Vui lòng điền đầy đủ thông tin đăng ký.';
        exit();
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($username) || empty($password) || empty($email) || empty($phone)) {
        echo 'Vui lòng điền đầy đủ thông tin đăng ký.';
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Vui lòng sử dụng địa chỉ email hợp lệ.';
        exit();
    }

    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo 'Email này đã được đăng ký.';
        exit();
    }
    $check_username_sql = "SELECT id FROM users WHERE username = ?";
    $check_username_stmt = $conn->prepare($check_username_sql);
    $check_username_stmt->bind_param("s", $username);
    $check_username_stmt->execute();
    $username_result = $check_username_stmt->get_result();

    if ($username_result && $username_result->num_rows > 0) {
        echo 'Tên đăng nhập này đã tồn tại.';
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);

    if ($stmt->execute()) {
        header('Location: ../login.php?register=success');
        exit();
    } else {
        echo 'Lỗi: ' . $conn->error;
    }
}
