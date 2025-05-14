<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để đặt hàng'
    ]);
    exit();
}

$user_id = $_SESSION['user']['id'];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$note = $_POST['note'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$total = $_POST['total'] ?? 0;

switch ($payment_method) {
    case 'Cash on Delivery':
        $payment_method = 'cad';
        break;
    case 'Bank Transfer':
        $payment_method = 'credit_card';
        break;
    case 'MoMo':
        $payment_method = 'momo';
        break;
    default:
        $payment_method = 'cash';
        break;
}

if (empty($username) || empty($email) || empty($phone) || empty($address)) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng điền đầy đủ thông tin'
    ]);
    exit();
}

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, username, email, phone, address, note, payment_method, total, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
    ");
    $stmt->bind_param("issssssd", $user_id, $username, $email, $phone, $address, $note, $payment_method, $total);
    $stmt->execute();
    $order_id = $conn->insert_id;

    $stmt = $conn->prepare("
        SELECT c.*, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price) 
        VALUES (?, ?, ?, ?)
    ");
    foreach ($cart_items as $item) {
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }

    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();


    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Đặt hàng thành công',
        'order_id' => $order_id
    ]);
} catch (Exception $e) {
    $conn->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'Lỗi: ' . $e->getMessage()
    ]);
}
