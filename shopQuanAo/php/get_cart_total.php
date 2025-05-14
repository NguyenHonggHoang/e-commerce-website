<?php
require_once 'db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'total' => 0]);
    exit();
}

$user_id = $_SESSION['user']['id'];
$sql = "SELECT c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
while ($row = $result->fetch_assoc()) {
    $total += $row['quantity'] * $row['price'];
}

$total = $total + ($total * 0.05) + 5;
echo json_encode(['success' => true, 'total' => number_format($total, 2)]);
