<?php
session_start();
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

error_log("Add to cart request received: " . print_r($_POST, true));

if (!isset($_SESSION['user'])) {
    error_log("User not logged in");
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng'
    ]);
    exit();
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
$user_id = $_SESSION['user']['id'];

error_log("Processing request - Product ID: $product_id, Quantity: $quantity, User ID: $user_id");

if ($product_id <= 0 || $quantity <= 0) {
    error_log("Invalid input - Product ID: $product_id, Quantity: $quantity");
    echo json_encode([
        'success' => false,
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit();
}

try {
    $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        error_log("Product not found - ID: $product_id");
        echo json_encode([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại'
        ]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_item = $result->fetch_assoc();

    if ($cart_item) {
        error_log("Updating existing cart item - Cart ID: {$cart_item['id']}");
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
    } else {
        error_log("Adding new item to cart");
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }

    if ($stmt->execute()) {
        error_log("Cart update successful");
        $stmt = $conn->prepare("
            SELECT SUM(c.quantity * p.price) as total 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];

        error_log("New cart total: $total");
        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart_total' => number_format($total, 0, ',', '.')
        ]);
    } else {
        error_log("Cart update failed: " . $stmt->error);
        echo json_encode([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi thêm vào giỏ hàng'
        ]);
    }
} catch (Exception $e) {
    error_log("Exception occurred: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi: ' . $e->getMessage()
    ]);
}
