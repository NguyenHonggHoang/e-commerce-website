<?php
session_start();
require_once 'php/db.php';

if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit();
}

$user_id = $_SESSION['user']['id'];

$sql = "SELECT p.*, c.quantity as cart_quantity 
        FROM products p 
        LEFT JOIN cart c ON p.id = c.product_id AND c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

$cart_items = array_filter($products, function ($item) {
	return $item['cart_quantity'] > 0;
});

$total = 0;
foreach ($cart_items as $item) {
	$total += $item['price'] * $item['cart_quantity'];
}

$tax = $total * 0.05;
$shipping = 5.00;
$grand_total = $total + $tax + $shipping;

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$_SESSION['cart_items'] = $cart_items;
$_SESSION['cart_total'] = $grand_total;

error_log("User ID: " . $user_id);
error_log("Cart Items: " . print_r($cart_items, true));
error_log("Total: " . $total);
error_log("Tax: " . $tax);
error_log("Shipping: " . $shipping);
error_log("Grand Total: " . $grand_total);

$is_cart_empty = true;
$user_cash = 0;

if (isset($_SESSION['user'])) {
	$user_id = $_SESSION['user']['id'];

	$sql = "SELECT cash FROM users WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$user_cash = $result->fetch_assoc()['cash'] ?? 0;


	$sql = "SELECT SUM(c.quantity * p.price) as total 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$cart_total = $result->fetch_assoc()['total'] ?? 0;

	$sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$cart_count = $result->fetch_assoc()['count'];
	$is_cart_empty = $cart_count == 0;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Checkout - ShopQuanAo</title>
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<!-- Custom Theme files -->
	<!--theme-style-->
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<!--//theme-style-->
	<meta name="keywords" content="Shopin Responsive web template, Bootstrap Web Templates, Flat Web Templates, AndroId Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
	<script
		type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!--theme-style-->
	<link href="css/style4.css" rel="stylesheet" type="text/css" media="all" />
	<!--//theme-style-->
	<script src="js/jquery.min.js"></script>
	<!--- start-rate---->
	<script src="js/jstarbox.js"></script>
	<link rel="stylesheet" href="css/jstarbox.css" type="text/css" media="screen" charset="utf-8" />
	<script type="text/javascript">
		jQuery(function() {
			jQuery('.starbox').each(function() {
				var starbox = jQuery(this);
				starbox.starbox({
					average: starbox.attr('data-start-value'),
					changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
					ghosting: starbox.hasClass('ghosting'),
					autoUpdateAverage: starbox.hasClass('autoupdate'),
					buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
					stars: starbox.attr('data-star-count') || 5
				}).bind('starbox-value-changed', function(event, value) {
					if (starbox.hasClass('random')) {
						var val = Math.random();
						starbox.next().text(' ' + val);
						return val;
					}
				})
			});
		});
	</script>
	<!---//End-rate---->
	<style>
		.checkout-container {
			padding: 40px 0;
		}

		.checkout-section {
			background: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			padding: 20px;
			margin-bottom: 20px;
		}

		.checkout-section h3 {
			color: #333;
			margin-bottom: 20px;
			padding-bottom: 10px;
			border-bottom: 2px solid #f5f5f5;
		}

		.cart-item {
			display: flex;
			align-items: center;
			padding: 15px 0;
			border-bottom: 1px solid #f5f5f5;
		}

		.cart-item img {
			width: 80px;
			height: 80px;
			object-fit: cover;
			margin-right: 15px;
		}

		.cart-item-details {
			flex-grow: 1;
		}

		.cart-item-title {
			font-weight: 600;
			margin-bottom: 5px;
		}

		.cart-item-price {
			color: #666;
		}

		.cart-item-quantity {
			color: #666;
		}

		.cart-item-total {
			font-weight: 600;
			color: #333;
		}

		.form-group {
			margin-bottom: 20px;
		}

		.form-control {
			border-radius: 4px;
			border: 1px solid #ddd;
			padding: 10px;
		}

		.form-control:focus {
			border-color: #80bdff;
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
		}

		.btn-checkout {
			background: #4CAF50;
			color: white;
			padding: 12px 30px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			width: 100%;
		}

		.btn-checkout:hover {
			background: #45a049;
		}

		.order-summary {
			background: #f9f9f9;
			padding: 20px;
			border-radius: 4px;
		}

		.order-summary-item {
			display: flex;
			justify-content: space-between;
			margin-bottom: 10px;
		}

		.order-total {
			font-size: 20px;
			font-weight: 600;
			color: #333;
			margin-top: 20px;
			padding-top: 20px;
			border-top: 2px solid #ddd;
		}

		.payment-methods {
			display: flex;
			gap: 10px;
			margin-bottom: 20px;
		}

		.payment-method {
			flex: 1;
			padding: 15px;
			border: 1px solid #ddd;
			border-radius: 4px;
			text-align: center;
			cursor: pointer;
		}

		.payment-method.active {
			border-color: #4CAF50;
			background: #f0f9f0;
		}

		.payment-method img {
			max-width: 50px;
			margin-bottom: 10px;
		}
	</style>
</head>

<body>
	<!--header-->
	<div class="header">
		<div class="container">
			<div class="head">
				<div class=" logo">
					<a href="index.php"><img src="images/logo.png" alt=""></a>
				</div>
			</div>
		</div>
		<div class="header-top">
			<div class="container">
				<div class="col-sm-5 col-md-offset-2  header-login">
					<ul>
						<?php if (isset($_SESSION['user'])): ?>
							<li class="dropdown user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img src="images/user.png" alt="User" style="width:32px;height:32px;border-radius:50%;">
								</a>
								<ul class="dropdown-menu">
									<li><a href="#" style="color: black;">Chỉnh sửa thông tin</a></li>
									<li><a href="php/logout.php" style="color: black;">Đăng xuất</a></li>
								</ul>
							</li>
						<?php else: ?>
							<li><a href="login.php">Login</a></li>
							<li><a href="register.php">Register</a></li>
						<?php endif; ?>
						<li><a href="checkout.php">Checkout</a></li>
					</ul>
				</div>

				<div class="col-sm-5 header-social">
					<ul>
						<li><a href="#"><i></i></a></li>
						<li><a href="#"><i class="ic1"></i></a></li>
						<li><a href="#"><i class="ic2"></i></a></li>
						<li><a href="#"><i class="ic3"></i></a></li>
						<li><a href="#"><i class="ic4"></i></a></li>
					</ul>

				</div>
				<div class="clearfix"> </div>
			</div>
		</div>

		<div class="container">

			<div class="head-top">

				<div class="col-sm-8 col-md-offset-2 h_menu4">
					<nav class="navbar nav_bottom" role="navigation">

						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header nav_2">
							<button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse"
								data-target="#bs-megadropdown-tabs">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>

						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
							<ul class="nav navbar-nav nav_1">
								<li><a class="color" href="index.php">Home</a></li>

								<li class="dropdown mega-dropdown active">
									<a class="color1" href="#" class="dropdown-toggle" data-toggle="dropdown">Women<span
											class="caret"></span></a>
									<div class="dropdown-menu ">
										<div class="menu-top">
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu1</h4>
													<ul>
														<li><a href="product.php">Accessories</a></li>
														<li><a href="product.php">Bags</a></li>
														<li><a href="product.php">Caps & Hats</a></li>
														<li><a href="product.php">Hoodies & Sweatshirts</a></li>

													</ul>
												</div>
											</div>
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu2</h4>
													<ul>
														<li><a href="product.php">Jackets & Coats</a></li>
														<li><a href="product.php">Jeans</a></li>
														<li><a href="product.php">Jewellery</a></li>
														<li><a href="product.php">Jumpers & Cardigans</a></li>
														<li><a href="product.php">Leather Jackets</a></li>
														<li><a href="product.php">Long Sleeve T-Shirts</a></li>
													</ul>
												</div>
											</div>
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu3</h4>
													<ul>
														<li><a href="product.php">Shirts</a></li>
														<li><a href="product.php">Shoes, Boots & Trainers</a></li>
														<li><a href="product.php">Sunglasses</a></li>
														<li><a href="product.php">Sweatpants</a></li>
														<li><a href="product.php">Swimwear</a></li>
														<li><a href="product.php">Trousers & Chinos</a></li>

													</ul>

												</div>
											</div>
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu4</h4>
													<ul>
														<li><a href="product.php">T-Shirts</a></li>
														<li><a href="product.php">Underwear & Socks</a></li>
														<li><a href="product.php">Vests</a></li>
														<li><a href="product.php">Jackets & Coats</a></li>
														<li><a href="product.php">Jeans</a></li>
														<li><a href="product.php">Jewellery</a></li>
													</ul>
												</div>
											</div>
											<div class="col1 col5">
												<img src="images/me.png" class="img-responsive" alt="">
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</li>
								<li class="dropdown mega-dropdown active">
									<a class="color2" href="#" class="dropdown-toggle" data-toggle="dropdown">Men<span
											class="caret"></span></a>
									<div class="dropdown-menu mega-dropdown-menu">
										<div class="menu-top">
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu1</h4>
													<ul>
														<li><a href="product.php">Accessories</a></li>
														<li><a href="product.php">Bags</a></li>
														<li><a href="product.php">Caps & Hats</a></li>
														<li><a href="product.php">Hoodies & Sweatshirts</a></li>

													</ul>
												</div>
											</div>
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu2</h4>
													<ul>
														<li><a href="product.php">Jackets & Coats</a></li>
														<li><a href="product.php">Jeans</a></li>
														<li><a href="product.php">Jewellery</a></li>
														<li><a href="product.php">Jumpers & Cardigans</a></li>
														<li><a href="product.php">Leather Jackets</a></li>
														<li><a href="product.php">Long Sleeve T-Shirts</a></li>
													</ul>
												</div>
											</div>
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu3</h4>

													<ul>
														<li><a href="product.php">Shirts</a></li>
														<li><a href="product.php">Shoes, Boots & Trainers</a></li>
														<li><a href="product.php">Sunglasses</a></li>
														<li><a href="product.php">Sweatpants</a></li>
														<li><a href="product.php">Swimwear</a></li>
														<li><a href="product.php">Trousers & Chinos</a></li>

													</ul>

												</div>
											</div>
											<div class="col1">
												<div class="h_nav">
													<h4>Submenu4</h4>
													<ul>
														<li><a href="product.php">T-Shirts</a></li>
														<li><a href="product.php">Underwear & Socks</a></li>
														<li><a href="product.php">Vests</a></li>
														<li><a href="product.php">Jackets & Coats</a></li>
														<li><a href="product.php">Jeans</a></li>
														<li><a href="product.php">Jewellery</a></li>
													</ul>
												</div>
											</div>
											<div class="col1 col5">
												<img src="images/me1.png" class="img-responsive" alt="">
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</li>
								<li><a class="color3" href="product.php">Sale</a></li>
								<li><a class="color4" href="404.php">About</a></li>
								<li><a class="color5" href="typo.php">Short Codes</a></li>
								<li><a class="color6" href="contact.php">Contact</a></li>
							</ul>
						</div><!-- /.navbar-collapse -->

					</nav>
				</div>
				<div class="col-sm-2 search-right">
					<ul class="heart" style="width: 40%; display: flex; padding-right: 5px;">
						<li>
							<a href="wishlist.php">
								<span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
							</a>
						</li>
						<li>
							<a href="#" id="search-toggle">
								<i class="glyphicon glyphicon-search"></i>
							</a>
						</li>
					</ul>
					<div class="cart box_1" style="width: 45%;">
						<a href="shopping-cart.php">
							<h3 style="display: flex">
								<div class="total" style="padding-right: 10px; padding-top: 5px;">
									<span>$<?php echo number_format($user_cash, 2); ?></span>
								</div>
								<img src="images/cart.png" alt="" />
							</h3>
						</a>
						<?php if ($is_cart_empty): ?>
							<p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>
						<?php endif; ?>
					</div>
					<div class="clearfix"> </div>

					<!-- Search Modal -->
					<div id="search-modal" class="modal fade" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Search Products</h4>
								</div>
								<div class="modal-body">
									<form action="search.php" method="GET">
										<div class="form-group">
											<input type="text" class="form-control" name="q" placeholder="Search products...">
										</div>
										<button type="submit" class="btn btn-primary">Search</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>

	<div class="checkout-container">
		<div class="container">
			<h2 class="text-center mb-4">Checkout</h2>

			<div class="row">
				<!-- Left Column - Order Details -->
				<div class="col-md-8">
					<div class="checkout-section">
						<h3>Order Details</h3>
						<?php if (empty($cart_items)): ?>
							<div class="alert alert-info">Your cart is empty. <a href="index.php">Continue shopping</a></div>
						<?php else: ?>
							<?php foreach ($cart_items as $item): ?>
								<div class="cart-item">
									<img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
									<div class="cart-item-details">
										<div class="cart-item-title"><?php echo htmlspecialchars($item['name']); ?></div>
										<div class="cart-item-price">$<?php echo number_format($item['price'], 2); ?></div>
										<div class="cart-item-quantity">
											Quantity: <?php echo $item['cart_quantity']; ?>
											<?php if ($item['cart_quantity'] > $item['quantity']): ?>
												<span class="text-danger">(Only <?php echo $item['quantity']; ?> available)</span>
											<?php endif; ?>
										</div>
									</div>
									<div class="cart-item-total">
										$<?php echo number_format($item['price'] * $item['cart_quantity'], 2); ?>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<div class="checkout-section">
						<h3>Shipping Information</h3>
						<form id="shipping-form">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Họ và tên</label>
										<input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Email</label>
										<input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Phone</label>
										<input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Address</label>
										<input type="text" class="form-control" name="address" placeholder="Địa chỉ" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Note</label>
								<textarea class="form-control" name="note" rows="3" placeholder="Add any special instructions..."></textarea>
							</div>
						</form>
					</div>

					<div class="checkout-section">
						<h3>Payment Method</h3>
						<div class="payment-methods">
							<div class="payment-method active">
								<img src="images/cod.png" alt="Cash on Delivery">
								<div>Cash on Delivery</div>
							</div>
							<div class="payment-method">
								<a href="credit.php" style="text-decoration: none; display: block;">
									<img src="images/banking.png" alt="Bank Transfer">
									<div>Bank Transfer</div>
								</a>
							</div>
							<div class="payment-method">
								<img src="images/momo.png" alt="MoMo">
								<div>MoMo</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Right Column - Order Summary -->
				<div class="col-md-4">
					<div class="checkout-section">
						<h3>Order Summary</h3>
						<div class="order-summary">
							<div class="order-summary-item">
								<span>Subtotal</span>
								<span>$<?php echo number_format($total, 2); ?></span>
							</div>
							<div class="order-summary-item">
								<span>Shipping</span>
								<span>$<?php echo number_format($shipping, 2); ?></span>
							</div>
							<div class="order-summary-item">
								<span>Tax</span>
								<span>$<?php echo number_format($tax, 2); ?></span>
							</div>
							<div class="order-total">
								<span>Total</span>
								<span>$<?php echo number_format($grand_total, 2); ?></span>
							</div>
						</div>
						<?php if (!empty($cart_items)): ?>
							<button class="btn-checkout" onclick="placeOrder()">Place Order</button>
						<?php else: ?>
							<button class="btn-checkout" disabled>Place Order</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="footer">
		<div class="footer-middle">
			<div class="container">
				<div class="col-md-3 footer-middle-in">
					<a href="index.php"><img src="images/log.png" alt=""></a>
					<p>Suspendisse sed accumsan risus. Curabitur rhoncus, elit vel tincidunt elementum, nunc urna
						tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor
						lorem.</p>
				</div>

				<div class="col-md-3 footer-middle-in">
					<h6>Information</h6>
					<ul class=" in">
						<li><a href="404.php">About</a></li>
						<li><a href="contact.php">Contact Us</a></li>
						<li><a href="#">Returns</a></li>
						<li><a href="contact.php">Site Map</a></li>
					</ul>
					<ul class="in in1">
						<li><a href="#">Order History</a></li>
						<li><a href="wishlist.php">Wish List</a></li>
						<li><a href="login.php">Login</a></li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="col-md-3 footer-middle-in">
					<h6>Tags</h6>
					<ul class="tag-in">
						<li><a href="#">Lorem</a></li>
						<li><a href="#">Sed</a></li>
						<li><a href="#">Ipsum</a></li>
						<li><a href="#">Contrary</a></li>
						<li><a href="#">Chunk</a></li>
						<li><a href="#">Amet</a></li>
						<li><a href="#">Omnis</a></li>
					</ul>
				</div>
				<div class="col-md-3 footer-middle-in">
					<h6>Newsletter</h6>
					<span>Sign up for News Letter</span>
					<form action="php/cart.php" method="POST">
						<input type="text" value="Enter your E-mail" onfocus="this.value='';"
							onblur="if (this.value == '') {this.value ='Enter your E-mail';}">
						<input type="submit" value="Subscribe">
					</form>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="container">
				<ul class="footer-bottom-top">
					<li><a href="#"><img src="images/f1.png" class="img-responsive" alt=""></a></li>
					<li><a href="#"><img src="images/f2.png" class="img-responsive" alt=""></a></li>
					<li><a href="#"><img src="images/f3.png" class="img-responsive" alt=""></a></li>
				</ul>
				<p class="footer-class">&copy; 2016 Shopin. All Rights Reserved | Design by <a
						href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!--//footer-->

	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		// Handle payment method selection
		$('.payment-method').click(function() {
			$('.payment-method').removeClass('active');
			$(this).addClass('active');
		});

		// Handle search toggle
		$('#search-toggle').click(function(e) {
			e.preventDefault();
			$('#search-modal').modal('show');
		});

		// Place order function
		function placeOrder() {
			const formData = new FormData(document.getElementById('shipping-form'));
			formData.append('payment_method', $('.payment-method.active div').text());
			formData.append('total', <?php echo number_format($grand_total, 2); ?>);

			fetch('php/place_order.php', {
					method: 'POST',
					body: formData
				})
				.then(response => {
					return response.text(); 
				})
				.then(text => {
					console.log(text); 
					return JSON.parse(text);
				})
				.then(data => {
					if (data.success) {
						alert('Order placed successfully!');
						window.location.href = 'orders.php?id=' + data.order_id;
					} else {
						alert('Error: ' + data.message);
					}
				})
				.catch(error => {
					console.error('Error:', error);
					alert('An error occurred while placing your order');
				});
		}
	</script>
</body>

</html>