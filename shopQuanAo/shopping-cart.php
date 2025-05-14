<?php
require_once 'php/db.php';
session_start();

if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit();
}

$user_id = $_SESSION['user']['id'];
$total = 0;

// Get cart items with product details
$sql = "SELECT c.id as cart_id, c.quantity, c.created_at, 
				p.id as product_id, p.name, p.price, p.image, p.description, p.quantity as stock_quantity
		FROM cart c 
		JOIN products p ON c.product_id = p.id 
		WHERE c.user_id = ? 
		ORDER BY c.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total
foreach ($cart_items as $item) {
	$total += $item['price'] * $item['quantity'];
}

// Store cart data in session
$_SESSION['cart_items'] = $cart_items;
$_SESSION['cart_total'] = $total;

// Debug information
error_log("User ID: " . $user_id);
error_log("Cart Items: " . print_r($cart_items, true));
error_log("Total: " . $total);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Shopping cart</title>
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800'>
	<link rel="stylesheet" href="/shopQuanAo/css/shopping-cart.css">
</head>

<body>
	<header id="site-header">
		<div class="container">
			<h1>Shopping cart</h1>
			<a href="index.php" class="continue-shopping">Continue Shopping</a>
		</div>
	</header>

	<div class="container">
		<section id="cart">
			<?php
			if (!empty($cart_items)) {
				foreach ($cart_items as $item) {
					$subtotal = $item['price'] * $item['quantity'];
			?>
					<article class="product">
						<header>
							<a class="remove" data-id="<?php echo $item['product_id']; ?>">
								<img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
								<h3>Remove product</h3>
							</a>
						</header>

						<div class="content">
							<h1><?php echo $item['name']; ?></h1>
							<?php echo $item['description']; ?>
						</div>

						<footer class="content">
							<span class="qt-minus" data-id="<?php echo $item['product_id']; ?>">-</span>
							<span class="qt"><?php echo $item['quantity']; ?></span>
							<span class="qt-plus" data-id="<?php echo $item['product_id']; ?>">+</span>

							<h2 class="full-price">
								<?php echo number_format($subtotal, 2); ?>$
							</h2>

							<h2 class="price">
								<?php echo number_format($item['price'], 2); ?>$
							</h2>
						</footer>
					</article>
			<?php
				}
			} else {
				echo '<div class="empty-cart">Your cart is empty</div>';
			}
			?>
		</section>
	</div>

	<footer id="site-footer">
		<div class="container clearfix">
			<div class="left">
				<h2 class="subtotal">Subtotal: <span><?php echo number_format($total, 2); ?></span>$</h2>
				<h3 class="tax">Taxes (5%): <span><?php echo number_format($total * 0.05, 2); ?></span>$</h3>
				<h3 class="shipping">Shipping: <span>5.00</span>$</h3>
			</div>

			<div class="right">
				<h1 class="total">Total: <span><?php echo number_format($total + ($total * 0.05) + 5, 2); ?></span>$</h1>
				<a href="checkout.php" style="display: inline-block; background-color: #008CBA; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 4px; font-weight: bold; transition: background-color 0.3s;">Checkout</a>
			</div>
		</div>
	</footer>

	<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<script src="/shopQuanAo/js/shopping-cart.js"></script>

	<style>
		#site-header .container {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.continue-shopping {
			background-color: #4CAF50;
			color: white;
			padding: 10px 20px;
			text-decoration: none;
			border-radius: 4px;
			font-weight: bold;
			transition: background-color 0.3s;
		}

		.continue-shopping:hover {
			background-color: #45a049;
		}
	</style>

	<script>
		// Update cart total in parent window
		const total = document.querySelector('.total span').textContent;
		window.parent.postMessage({
			type: 'updateTotal',
			total: total
		}, '*');

		// Confirm before checkout
		document.querySelector('.btn').addEventListener('click', function(e) {
			if (!confirm('Proceed to checkout?')) {
				e.preventDefault();
			}
		});
	</script>
</body>

</html>