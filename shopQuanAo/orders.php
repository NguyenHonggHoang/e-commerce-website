<?php
session_start();
require_once 'php/db.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

// Get user's orders
$sql = "SELECT o.*, 
        COUNT(oi.id) as total_items,
        GROUP_CONCAT(CONCAT(p.name, ' (', oi.quantity, ')') SEPARATOR ', ') as items
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();

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
    <title>Order History - Shopin</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style4.css" rel="stylesheet" type="text/css" media="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .order-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-items {
            margin: 10px 0;
            color: #666;
        }

        .order-total {
            font-weight: bold;
            color: #333;
            text-align: right;
        }

        .order-date {
            color: #888;
            font-size: 0.9em;
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

    <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        <h2>Order History</h2>
        <div class="row">
            <?php if ($orders->num_rows > 0): ?>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <div class="col-md-12">
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <h4>Order #<?php echo $order['id']; ?></h4>
                                    <div class="order-date">
                                        Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                    </div>
                                </div>
                                <span class="order-status status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            <div class="order-items">
                                <strong>Items:</strong> <?php echo $order['items']; ?>
                            </div>
                            <div class="order-total">
                                Total: $<?php echo number_format($order['total'], 2); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        You haven't placed any orders yet.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!--//footer-->
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
</body>

</html>