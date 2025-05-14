
<?php session_start(); ?>
<?php
require_once 'php/db.php';
$products = $conn->query("SELECT * FROM products"); // Lấy tất cả sản phẩm
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
<!--A Design by W3layouts 
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>

<head>
	<title>Shopin A Ecommerce Category Flat Bootstrap Responsive Website Template | Products :: w3layouts</title>
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
	<style>
		/* Đảm bảo các thẻ sản phẩm đều nhau */
		.mid-popular {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
		}

		.item-grid {
			display: flex;
			flex-direction: column;
			height: 100%;
			min-width: 220px;
			max-width: 250px;
			flex: 1 1 220px;
			margin-bottom: 20px;
		}

		.mid-pop {
			flex: 1 1 auto;
			display: flex;
			flex-direction: column;
			height: 100%;
			background: #fff;
			border: 1px solid #eee;
			border-radius: 8px;
			padding: 10px;
			box-sizing: border-box;
		}

		.pro-img {
			width: 100%;
			height: 180px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 10px;
		}

		.pro-img img {
			max-width: 100%;
			max-height: 100%;
			width: auto;
			height: 100%;
			object-fit: cover;
			border-radius: 6px;
		}
	</style>
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

					<!---pop-up-box---->
					<link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all" />
					<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
					<!---//pop-up-box---->
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
			<h1>Products</h1>
			<em></em>
			<h2><a href="index.php">Home</a><label>/</label>Products</h2>
		</div>
	</div>
	<!--content-->
	<div class="product">
		<div class="container">
			<div class="col-md-9">
				<div class="mid-popular">
					<?php while ($row = $products->fetch_assoc()): ?>
						<div class="col-md-3 item-grid simpleCart_shelfItem">
							<form class="add-to-cart-form" style="position:relative;">
								<div class="mid-pop">
									<div class="pro-img">
										<img src="images/<?= htmlspecialchars($row['image']) ?>" class="img-responsive" alt=""
											id="real-product-image-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>" quickbeam="image" />
										<div class="zoom-icon">
											<a class="picture" href="images/<?= htmlspecialchars($row['image']) ?>" class="b-link-stripe b-animate-go thickbox">
												<i class="glyphicon glyphicon-search icon "></i>
											</a>
											<a href="single.php?id=<?= $row['id'] ?>">
												<i class="glyphicon glyphicon-menu-right icon"></i>
											</a>
										</div>
									</div>
									<div class="mid-1">
										<div class="women">
											<div class="women-top">
												<span><?= htmlspecialchars($row['category']) ?></span>
												<h6><a href="single.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a></h6>
											</div>
											<div class="img item_add">
												<button type="button" class="btn btn-link real-add-to-cart" data-id="<?= $row['id'] ?>"
													style="padding: 0; border: none; background: none;" quickbeam="add-to-cart">
													<img src="images/ca.png" alt="">
												</button>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="mid-2">
											<p>
												<label>$<?= htmlspecialchars($row['old_price']) ?></label>
												<em class="item_price" id="real-product-price-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>" quickbeam="price">
													$<?= htmlspecialchars($row['price']) ?>
												</em>
											</p>
											<div class="block">
												<div class="starbox small ghosting"> </div>
											</div>
											<input type="hidden" name="quantity" value="1" class="real-quantity-selector" data-id="<?= $row['id'] ?>">
											<input type="hidden" name="product_id" value="<?= $row['id'] ?>">
											<div class="clearfix"></div>
										</div>
									</div>
									<!-- Quickbeam hidden elements -->
									<span class="img" quickbeam="image" id="qb-image-<?= $row['id'] ?>"
										style="display:none;background-image:url('images/<?= htmlspecialchars($row['image']) ?>')"
										data-src="images/<?= htmlspecialchars($row['image']) ?>"></span>
									<div class="price" quickbeam="price" quickbeam-price="<?= htmlspecialchars($row['price']) ?>"
										id="qb-price-<?= $row['id'] ?>" style="display:none;">
										$<?= htmlspecialchars($row['price']) ?>
									</div>
									<input type="text" class="quantity-selector" id="qb-qty-<?= $row['id'] ?>" value="1" style="display:none;">
									<div class="btn btn-primary add-to-cart-btn" quickbeam="add-to-cart" data-id="<?= $row['id'] ?>"
										id="qb-add-<?= $row['id'] ?>" style="display:none;"><span>Add to Cart</span></div>
								</div>
							</form>
						</div>
					<?php endwhile; ?>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="col-md-3 product-bottom">
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
							<label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Upto - 10%
								(20)</label>
						</div>
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>40% - 50% (5)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>30% - 20% (7)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>10% - 5% (2)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Other(50)</label>
						</div>
					</div>
				</section>


				<!---->
				<section class="sky-form">
					<h4 class="cate">Type</h4>
					<div class="row row1 scroll-pane">
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Sofa Cum
								Beds (30)</label>
						</div>
						<div class="col col-4">
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Bags (30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Caps & Hats
								(30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Jackets & Coats
								(30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Jeans (30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Shirts (30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Sunglasses
								(30)</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Swimwear (30)</label>
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
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>New Balance</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Paul Smith</label>
							<label class="checkbox"><input type="checkbox" name="checkbox"><i></i>Ray-Ban</label>
						</div>
					</div>
				</section>
			</div>
			<div class="clearfix"></div>
		</div>
		<!--products-->

		<!--//products-->
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
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

	<script src="js/simpleCart.min.js"> </script>
	<!-- slide -->
	<script src="js/bootstrap.min.js"></script>
	<!--light-box-files -->
	<script src="js/jquery.chocolat.js"></script>
	<link rel="stylesheet" href="css/chocolat.css" type="text/css" media="screen" charset="utf-8">
	<!--light-box-files -->
	<script type="text/javascript" charset="utf-8">
		$(function() {
			$('a.picture').Chocolat();
		});
	</script>
	<!-- Quickbeam cart-->
	<div id="quick-cart" quickbeam="cart">
		<a id="quick-cart-pay" quickbeam="cart-pay" class="cart-ico">
			<span>
				<strong class="quick-cart-text">Pay<br></strong>
				<span id="quick-cart-price">0</span>
				<span id="quick-cart-pay-total-count">0</span>
			</span>
		</a>
	</div>
	<script>
		// Initialize cart animation
		document.addEventListener('DOMContentLoaded', function() {
			if (typeof Quickbeam !== 'undefined') {
				console.log('Initializing Quickbeam...');
				Quickbeam.init({});
			} else {
				console.error('Quickbeam not loaded!');
			}
		});
	</script>
	<!-- Quickbeam cart-->
	<div id="quick-cart" quickbeam="cart">
		<a id="quick-cart-pay" quickbeam="cart-pay" class="cart-ico">
			<span>
				<strong class="quick-cart-text">Pay<br></strong>
				<span id="quick-cart-price">0</span>
				<span id="quick-cart-pay-total-count">0</span>
			</span>
		</a>
	</div>
	<!-- Quickbeam cart end -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
	<script src="/shopQuanAo/js/sc.js"></script>
</body>

</html>