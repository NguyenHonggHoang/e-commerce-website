<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo 'Vui lòng nhập đầy đủ thông tin đăng nhập.';
        exit();
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo 'Vui lòng nhập đầy đủ thông tin đăng nhập.';
        exit();
    }

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'phone' => $user['phone']
            ];
            header('Location: ../index.php');
            exit();
        }
    }
    echo 'Sai tên đăng nhập hoặc mật khẩu.';
}
