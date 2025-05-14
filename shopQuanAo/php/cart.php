<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please login to continue'
    ]);
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required parameters'
        ]);
        exit();
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $action = isset($_POST['action']) ? $_POST['action'] : 'update';

    try {
        $check_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $cart_item = $result->fetch_assoc();
            $current_quantity = $cart_item['quantity'];

            if ($action === 'remove' || $quantity === 0) {
                $delete_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("ii", $user_id, $product_id);
                $delete_stmt->execute();

                echo json_encode([
                    'success' => true,
                    'message' => 'Product removed from cart',
                    'quantity' => 0
                ]);
            } else {
                $new_quantity = $current_quantity + $quantity;

                if ($new_quantity > 0) {
                    $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
                    $update_stmt->execute();

                    echo json_encode([
                        'success' => true,
                        'message' => 'Cart updated successfully',
                        'quantity' => $new_quantity
                    ]);
                } else {
                    $delete_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("ii", $user_id, $product_id);
                    $delete_stmt->execute();

                    echo json_encode([
                        'success' => true,
                        'message' => 'Product removed from cart',
                        'quantity' => 0
                    ]);
                }
            }
        } else {
            if ($quantity > 0) {
                $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
                $insert_stmt->execute();

                echo json_encode([
                    'success' => true,
                    'message' => 'Product added to cart successfully',
                    'quantity' => $quantity
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid quantity'
                ]);
            }
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
