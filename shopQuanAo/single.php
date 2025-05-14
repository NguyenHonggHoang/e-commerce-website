<?php session_start(); ?>
<?php
require_once 'php/db.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details
$sql = "SELECT p.*, GROUP_CONCAT(pt.tab_content) as tab_contents 
        FROM products p 
        LEFT JOIN product_tabs pt ON p.id = pt.product_id 
        WHERE p.id = ? 
        GROUP BY p.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
	header("Location: index.php");
	exit();
}

// Split tab contents into array
$tab_contents = explode(',', $product['tab_contents']);

// Initialize cart variables
$is_cart_empty = true;
$user_cash = 0;

// Check if user is logged in and get cart data
if (isset($_SESSION['user'])) {
	$user_id = $_SESSION['user']['id'];

	// Get user's cash
	$sql = "SELECT cash FROM users WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$user_cash = $result->fetch_assoc()['cash'] ?? 0;

	// Get cart total for the user
	$sql = "SELECT SUM(c.quantity * p.price) as total 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$cart_total = $result->fetch_assoc()['total'] ?? 0;

	// Check if cart is empty
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
<html>

<head>
	<title>Shopin A Ecommerce Category Flat Bootstrap Responsive Website Template | Single :: w3layouts</title>
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<!-- Custom Theme files -->
	<!--theme-style-->
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<!--//theme-style-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	<style>
		.modal-success {
			display: none;
			position: fixed;
			z-index: 9999;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
		}

		.modal-content {
			background-color: #fff;
			margin: 15% auto;
			padding: 20px;
			border: 1px solid #888;
			width: 300px;
			border-radius: 5px;
			text-align: center;
			position: relative;
			animation: modalFadeIn 0.3s;
		}

		@keyframes modalFadeIn {
			from {
				opacity: 0;
				transform: translateY(-20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.modal-content i {
			color: #4CAF50;
			font-size: 48px;
			margin-bottom: 15px;
		}

		.modal-content h3 {
			color: #333;
			margin-bottom: 10px;
		}

		.modal-content p {
			color: #666;
			margin-bottom: 20px;
		}

		.modal-content .btn {
			background-color: #4CAF50;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 3px;
			cursor: pointer;
			text-decoration: none;
			display: inline-block;
		}

		.modal-content .btn:hover {
			background-color: #45a049;
		}
	</style>
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
	<link href="css/form.css" rel="stylesheet" type="text/css" media="all" />
	<link href="/shopQuanAo/css/haha.css" rel="stylesheet" type="text/css" media="all" />
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
						<li><a class="play-icon popup-with-zoom-anim" href="#small-dialog"><i
									class="glyphicon glyphicon-search"> </i></a></li>
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

					<!----->

					<link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all" />
					<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
					<div id="small-dialog" class="mfp-hide">
						<div class="search-top">
							<div class="login-search">
								<input type="submit" value="">
								<input type="text" value="Search.." onfocus="this.value = '';"
									onblur="if (this.value == '') {this.value = 'Search..';}">
							</div>
							<p>Shopin</p>
						</div>
					</div>
					<script>
						$(document).ready(function() {
							$('.popup-with-zoom-anim').magnificPopup({
								type: 'inline',
								fixedContentPos: false,
								fixedBgPos: true,
								overflowY: 'auto',
								closeBtnInside: true,
								preloader: false,
								midClick: true,
								removalDelay: 300,
								mainClass: 'my-mfp-zoom-in'
							});

						});
					</script>
					<!----->
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<!--banner-->
	<div class="banner-top">
		<div class="container">
			<h1>Single</h1>
			<em></em>
			<h2><a href="index.html">Home</a><label>/</label>Single</h2>
		</div>
	</div>
	<div class="single">

		<div class="container">
			<div class="col-md-9">
				<div class="col-md-5 grid">
					<div class="flexslider">
						<ul class="slides">
							<li data-thumb="<?php echo htmlspecialchars($product['image']); ?>">
								<div class="thumb-image"> <img src="images/<?php echo htmlspecialchars($product['image']); ?>" data-imagezoom="true"
										class="img-responsive"> </div>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-md-7 single-top-in">
					<div class="span_2_of_a1 simpleCart_shelfItem">
						<h3><?php echo htmlspecialchars($product['name']); ?></h3>
						<p class="in-para"><?php echo htmlspecialchars($product['description']); ?></p>
						<div class="price_single">
							<span class="reducedfrom item_price">$<?php echo number_format($product['price'], 0, ',', '.'); ?></span>
							<?php if ($product['old_price'] > 0): ?>
								<span class="old-price" style="padding-left: 10px; text-decoration: line-through;">$<?php echo number_format($product['old_price'], 0, ',', '.'); ?> </span>
							<?php endif; ?>
							<div class="clearfix"></div>
						</div>
						<h4 class="quick">Quick Overview:</h4>
						<p class="quick_desc"><?php echo htmlspecialchars($product['quick_overview']); ?></p>
						<div class="wish-list">
							<ul>
								<li class="wish"><a href="#"><span class="glyphicon glyphicon-check"
											aria-hidden="true"></span>Add to Wishlist</a></li>
								<li class="compare"><a href="#"><span class="glyphicon glyphicon-resize-horizontal"
											aria-hidden="true"></span>Add to Compare</a></li>
							</ul>
						</div>
						<div class="quantity">
							<div class="quantity-select">
								<div class="entry value-minus">&nbsp;</div>
								<div class="entry value"><span>1</span></div>
								<div class="entry value-plus active">&nbsp;</div>
							</div>
						</div>
						<!--quantity-->
						<script>
							$(document).ready(function() {
								console.log('Document ready');

								// Debug button existence
								console.log('Add to cart button exists:', $('.add-to-cart').length > 0);

								$('.value-plus').on('click', function() {
									var divUpd = $(this).parent().find('.value'),
										newVal = parseInt(divUpd.text(), 10) + 1;
									divUpd.text(newVal);
									console.log('Quantity increased to:', newVal);
								});

								$('.value-minus').on('click', function() {
									var divUpd = $(this).parent().find('.value'),
										newVal = parseInt(divUpd.text(), 10) - 1;
									if (newVal >= 1) {
										divUpd.text(newVal);
										console.log('Quantity decreased to:', newVal);
									}
								});

								// Add to cart functionality
								$(document).on('click', '.add-to-cart', function(e) {
									console.log('Add to cart button clicked - using document.on');
									e.preventDefault();

									// Get quantity from the value div
									const quantityDiv = $('.value');
									const quantity = parseInt(quantityDiv.text().trim(), 10);
									console.log('Selected quantity:', quantity);

									if (isNaN(quantity) || quantity < 1) {
										console.log('Invalid quantity:', quantity);
										alert('Vui lòng chọn số lượng hợp lệ');
										return;
									}

									// Create form data
									const formData = new FormData();
									formData.append('product_id', <?php echo $product['id']; ?>);
									formData.append('quantity', quantity);
									console.log('Product ID:', <?php echo $product['id']; ?>);

									console.log('Sending request to add_to_cart.php...');
									fetch('php/add_to_cart.php', {
											method: 'POST',
											body: formData
										})
										.then(response => {
											console.log('Response received:', response);
											return response.json();
										})
										.then(data => {
											console.log('Response data:', data);
											if (data.success) {
												console.log('Successfully added to cart');
												showSuccessModal();
												// Update cart total if needed
												if (data.cart_total) {
													console.log('Updating cart total to:', data.cart_total);
													$('.total span').text('$' + data.cart_total);
												}
											} else {
												console.log('Error adding to cart:', data.message);
												alert('Có lỗi xảy ra: ' + data.message);
											}
										})
										.catch(error => {
											console.error('Fetch error:', error);
											alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
										});
								});
							});
						</script>
						<!--quantity-->

						<button class="add-to add-to-cart hvr-skew-backward">Add to cart</button>
					</div>

				</div>
				<div class="clearfix"> </div>
				<!---->
				<?php
				$product_id = $_GET['id'] ?? 0;
				$tab_contents = [];

				$sql = "SELECT tab_key, tab_content FROM product_tabs WHERE product_id = ? ORDER BY tab_key ASC";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("i", $product_id);
				$stmt->execute();
				$result = $stmt->get_result();

				while ($row = $result->fetch_assoc()) {
					if ($row['tab_key'] === 'tab1') $tab_contents[0] = $row['tab_content'];
					if ($row['tab_key'] === 'tab2') $tab_contents[1] = $row['tab_content'];
					if ($row['tab_key'] === 'tab3') $tab_contents[2] = $row['tab_content'];
				}

				?>

				<div class="tab-head">
					<nav class="nav-sidebar">
						<ul class="nav tabs">
							<li class="active"><a href="#tab1" data-toggle="tab">Product Description</a></li>
							<li><a href="#tab2" data-toggle="tab">Additional Information</a></li>
							<li><a href="#tab3" data-toggle="tab">Reviews</a></li>
						</ul>
					</nav>

					<div class="tab-content one">
						<div class="tab-pane active text-style" id="tab1">
							<div class="facts">
								<?= $tab_contents[0] ?? '' ?>
							</div>
						</div>
						<div class="tab-pane text-style" id="tab2">
							<div class="facts">
								<?= $tab_contents[1] ?? '' ?>
							</div>
						</div>
						<div class="tab-pane text-style" id="tab3">
							<div class="facts">
								<?= $tab_contents[2] ?? '' ?>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>

				<div class="clearfix"></div>
			</div>
			<!----->

			<div class="col-md-3 product-bottom product-at">
				<!--categories-->
				<div class=" rsidebar span_1_of_left">
					<h4 class="cate">Categories</h4>
					<ul class="menu-drop">
						<li class="item1"><a href="#">Men </a>
							<ul class="cute">
								<li class="subitem1"><a href="product.html">Cute Kittens </a></li>
								<li class="subitem2"><a href="product.html">Strange Stuff </a></li>
								<li class="subitem3"><a href="product.html">Automatic Fails </a></li>
							</ul>
						</li>
						<li class="item2"><a href="#">Women </a>
							<ul class="cute">
								<li class="subitem1"><a href="product.html">Cute Kittens </a></li>
								<li class="subitem2"><a href="product.html">Strange Stuff </a></li>
								<li class="subitem3"><a href="product.html">Automatic Fails </a></li>
							</ul>
						</li>
						<li class="item3"><a href="#">Kids</a>
							<ul class="cute">
								<li class="subitem1"><a href="product.html">Cute Kittens </a></li>
								<li class="subitem2"><a href="product.html">Strange Stuff </a></li>
								<li class="subitem3"><a href="product.html">Automatic Fails</a></li>
							</ul>
						</li>
						<li class="item4"><a href="#">Accessories</a>
							<ul class="cute">
								<li class="subitem1"><a href="product.html">Cute Kittens </a></li>
								<li class="subitem2"><a href="product.html">Strange Stuff </a></li>
								<li class="subitem3"><a href="product.html">Automatic Fails</a></li>
							</ul>
						</li>

						<li class="item4"><a href="#">Shoes</a>
							<ul class="cute">
								<li class="subitem1"><a href="product.html">Cute Kittens </a></li>
								<li class="subitem2"><a href="product.html">Strange Stuff </a></li>
								<li class="subitem3"><a href="product.html">Automatic Fails </a></li>
							</ul>
						</li>
					</ul>
				</div>
				<!--initiate accordion-->
				<script type="text/javascript">
					$(function() {
						var menu_ul = $('.menu-drop > li > ul'),
							menu_a = $('.menu-drop > li > a');
						menu_ul.hide();
						menu_a.click(function(e) {
							e.preventDefault();
							if (!$(this).hasClass('active')) {
								menu_a.removeClass('active');
								menu_ul.filter(':visible').slideUp('normal');
								$(this).addClass('active').next().stop(true, true).slideDown('normal');
							} else {
								$(this).removeClass('active');
								$(this).next().stop(true, true).slideUp('normal');
							}
						});

					});
				</script>
				<!--//menu-->
				<section class="sky-form">
					<h4 class="cate">Discounts</h4>
					<div class="row row1 scroll-pane">
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Upto -
								10% (20)</label>
						</div>
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>40% - 50%
								(5)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>30% - 20%
								(7)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>10% - 5%
								(2)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Other(50)</label>
						</div>
					</div>
				</section>


				<!---->
				<section class="sky-form">
					<h4 class="cate">Type</h4>
					<div class="row row1 scroll-pane">
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Sofa
								Cum Beds (30)</label>
						</div>
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Bags (30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Caps & Hats
								(30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Jackets & Coats
								(30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Jeans (30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Shirts
								(30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Sunglasses
								(30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Swimwear
								(30)</label>
						</div>
					</div>
				</section>
				<section class="sky-form">
					<h4 class="cate">Brand</h4>
					<div class="row row1 scroll-pane">
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox"
									checked=""><i></i>Roadstar</label>
						</div>
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Levis</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Persol</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Nike</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Edwin</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>New
								Balance</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Paul Smith</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Ray-Ban</label>
						</div>
					</div>
				</section>
			</div>
			<div class="clearfix"> </div>
		</div>

		<!--brand-->
		<div class="container">
			<div class="brand">
				<div class="col-md-3 brand-grid">
					<img src="images/ic.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic1.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic2.png" class="img-responsive" alt="">
				</div>
				<div class="col-md-3 brand-grid">
					<img src="images/ic3.png" class="img-responsive" alt="">
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<!--//brand-->
	</div>

	<!--//content-->
	<!--//footer-->
	<div class="footer">
		<div class="footer-middle">
			<div class="container">
				<div class="col-md-3 footer-middle-in">
					<a href="index.html"><img src="images/log.png" alt=""></a>
					<p>Suspendisse sed accumsan risus. Curabitur rhoncus, elit vel tincidunt elementum, nunc urna
						tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor
						lorem.</p>
				</div>

				<div class="col-md-3 footer-middle-in">
					<h6>Information</h6>
					<ul class=" in">
						<li><a href="404.html">About</a></li>
						<li><a href="contact.html">Contact Us</a></li>
						<li><a href="#">Returns</a></li>
						<li><a href="contact.html">Site Map</a></li>
					</ul>
					<ul class="in in1">
						<li><a href="#">Order History</a></li>
						<li><a href="wishlist.html">Wish List</a></li>
						<li><a href="login.html">Login</a></li>
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
					<form>
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
	<script src="js/imagezoom.js"></script>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script defer src="js/jquery.flexslider.js"></script>
	<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

	<script>
		// Can also be used with $(document).ready()
		$(window).load(function() {
			$('.flexslider').flexslider({
				animation: "slide",
				controlNav: "thumbnails"
			});
		});
	</script>

	<script src="js/simpleCart.min.js"> </script>
	<!-- slide -->
	<script src="js/bootstrap.min.js"></script>

	<!-- Add this before closing body tag -->
	<div id="successModal" class="modal-success">
		<div class="modal-content">
			<i class="glyphicon glyphicon-ok-circle"></i>
			<h3>Thành công!</h3>
			<p>Đã thêm sản phẩm vào giỏ hàng</p>
			<a href="shopping-cart.php" class="btn">Xem giỏ hàng</a>
		</div>
	</div>

	<script>
		function showSuccessModal() {
			var modal = document.getElementById('successModal');
			modal.style.display = "block";
			setTimeout(function() {
				modal.style.display = "none";
			}, 3000);
		}

		// Close modal when clicking outside
		window.onclick = function(event) {
			var modal = document.getElementById('successModal');
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
	</script>
</body>

</html>