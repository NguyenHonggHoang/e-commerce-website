<?php session_start(); ?>
<?php
require_once 'php/db.php';

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
  <title>Shopin A Ecommerce Category Flat Bootstrap Responsive Website Template | Short Codes :: w3layouts</title>
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
  <!-- Custom Theme files -->
  <!--theme-style-->
  <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
  <!--//theme-style-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="keywords" content="Shopin Responsive web template, Bootstrap Web Templates, Flat Web Templates, AndroId Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
  <script type="application/x-javascript">
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
      <h1>Short Codes</h1>
      <em></em>
      <h2><a href="index.php">Home</a><label>/</label>Short Codes</h2>
    </div>
  </div>
  <!--content-->
  <div class="container">
    <div class="page">
      <!--button-->
      <div class="grid_3 grid_4">
        <div class="page-header">
          <h3>Headings</h3>
        </div>

        <div class="bs-example">
          <table class="table">
            <tbody>
              <tr>
                <td>
                  <h1 id="h1-bootstrap-heading">h1. Bootstrap heading<a class="anchorjs-link" href="#h1.-bootstrap-heading"><span class="anchorjs-icon"></span></a></h1>
                </td>
                <td class="type-info">Semibold 36px</td>
              </tr>
              <tr>
                <td>
                  <h2 id="h2-bootstrap-heading">h2. Bootstrap heading<a class="anchorjs-link" href="#h2.-bootstrap-heading"><span class="anchorjs-icon"></span></a></h2>
                </td>
                <td class="type-info">Semibold 30px</td>
              </tr>
              <tr>
                <td>
                  <h3 id="h3-bootstrap-heading">h3. Bootstrap heading<a class="anchorjs-link" href="#h3.-bootstrap-heading"><span class="anchorjs-icon"></span></a></h3>
                </td>
                <td class="type-info">Semibold 24px</td>
              </tr>
              <tr>
                <td>
                  <h4 id="h4.-bootstrap-heading">h4. Bootstrap heading<a class="anchorjs-link" href="#h4.-bootstrap-heading"><span class="anchorjs-icon"></span></a></h4>
                </td>
                <td class="type-info">Semibold 18px</td>
              </tr>
              <tr>
                <td>
                  <h5 id="h5-bootstrap-heading">h5. Bootstrap heading<a class="anchorjs-link" href="#h5.-bootstrap-heading"><span class="anchorjs-icon"></span></a></h5>
                </td>
                <td class="type-info">Semibold 14px</td>
              </tr>
              <tr>
                <td>
                  <h6 id="h6-bootstrap-heading">h6. Bootstrap heading</h6>
                </td>
                <td class="type-info">Semibold 12px</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!--buttons-->
      <div class="page-header">
        <h3>Buttons</h3>
      </div>
      <p class="grid1">
        <button type="button" class="btn btn-lg btn-default">Default</button>
        <button type="button" class="btn btn-lg btn-primary">Primary</button>
        <button type="button" class="btn btn-lg btn-success">Success</button>
        <button type="button" class="btn btn-lg btn-info">Info</button>
        <button type="button" class="btn btn-lg btn-warning">Warning</button>
        <button type="button" class="btn btn-lg btn-danger">Danger</button>
        <button type="button" class="btn btn-lg btn-link">Link</button>
      </p>
      <p class="grid1 ">
        <button type="button" class="btn btn-1 btn-default">Default</button>
        <button type="button" class="btn btn-1 btn-primary">Primary</button>
        <button type="button" class="btn btn-1 btn-success">Success</button>
        <button type="button" class="btn btn-1 btn-info">Info</button>
        <button type="button" class="btn btn-1 btn-warning">Warning</button>
        <button type="button" class="btn btn-1 btn-danger">Danger</button>
        <button type="button" class="btn btn-1 btn-link">Link</button>
      </p>
      <p class="grid1">
        <button type="button" class="btn btn-sm btn-default">Default</button>
        <button type="button" class="btn btn-sm btn-primary">Primary</button>
        <button type="button" class="btn btn-sm btn-success">Success</button>
        <button type="button" class="btn btn-sm btn-info">Info</button>
        <button type="button" class="btn btn-sm btn-warning">Warning</button>
        <button type="button" class="btn btn-sm btn-danger">Danger</button>
        <button type="button" class="btn btn-sm btn-link">Link</button>
      </p>
      <p class="grid1">
        <button type="button" class="btn btn-xs btn-default">Default</button>
        <button type="button" class="btn btn-xs btn-primary">Primary</button>
        <button type="button" class="btn btn-xs btn-success">Success</button>
        <button type="button" class="btn btn-xs btn-info">Info</button>
        <button type="button" class="btn btn-xs btn-warning">Warning</button>
        <button type="button" class="btn btn-xs btn-danger">Danger</button>
        <button type="button" class="btn btn-xs btn-link">Link</button>
      </p>
      <!--//button-->
      <!--bages-->
      <div class="page-header">
        <h3>Badges</h3>
      </div>
      <p>
        <a href="#">Inbox <span class="badge">42</span></a>
      </p>
      <ul class="nav nav-pills" role="tablist">
        <li role="presentation" class="active"><a href="#">Home <span class="badge">42</span></a></li>
        <li role="presentation"><a href="#">Profile</a></li>
        <li role="presentation"><a href="#">Messages <span class="badge">3</span></a></li>
      </ul>
      <!--//bages-->
      <!--alerts-->
      <div class="page-header">
        <h3>Alerts</h3>
      </div>
      <div class="alert alert-success" role="alert">
        <strong>Well done!</strong> You successfully read this important alert message.
      </div>
      <div class="alert alert-info" role="alert">
        <strong>Heads up!</strong> This alert needs your attention, but it's not super important.
      </div>
      <div class="alert alert-warning" role="alert">
        <strong>Warning!</strong> Best check yo self, you're not looking too good.
      </div>
      <div class="alert alert-danger" role="alert">
        <strong>Oh snap!</strong> Change a few things up and try submitting again.
      </div>
      <!--//alerts-->
      <!--nav-->
      <div class="page-header">
        <h3>Navs</h3>
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#">Home</a></li>
          <li role="presentation"><a href="#">Profile</a></li>
          <li role="presentation"><a href="#">Messages</a></li>
        </ul>
        <ul class="nav nav-pills" role="tablist">
          <li role="presentation" class="active"><a href="#">Home</a></li>
          <li role="presentation"><a href="#">Profile</a></li>
          <li role="presentation"><a href="#">Messages</a></li>
        </ul>
      </div>
      <!--//nav-->


      <!--Progress bars-->
      <div class="page-header">
        <h3>Progress bars</h3>
      </div>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"><span class="sr-only">60% Complete</span></div>
      </div>
      <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"><span class="sr-only">40% Complete (success)</span></div>
      </div>
      <div class="progress">
        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"><span class="sr-only">20% Complete</span></div>
      </div>
      <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"><span class="sr-only">60% Complete (warning)</span></div>
      </div>
      <div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"><span class="sr-only">80% Complete (danger)</span></div>
      </div>
      <div class="progress">
        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"><span class="sr-only">60% Complete</span></div>
      </div>
      <div class="progress">
        <div class="progress-bar progress-bar-success" style="width: 35%"><span class="sr-only">35% Complete (success)</span></div>
        <div class="progress-bar progress-bar-warning" style="width: 20%"><span class="sr-only">20% Complete (warning)</span></div>
        <div class="progress-bar progress-bar-danger" style="width: 10%"><span class="sr-only">10% Complete (danger)</span></div>
      </div>
      <!--//Progress bars-->
      <!--Panels-->
      <div class="page-header">
        <h3>Panels</h3>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
        </div><!-- /.col-sm-4 -->
        <div class="col-sm-4">
          <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
        </div><!-- /.col-sm-4 -->
        <div class="col-sm-4">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
          <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
        </div><!-- /.col-sm-4 -->
      </div>
      <!--//Panels-->
      <!--labels-->
      <div class="page-header">
        <h3>Labels</h3>
      </div>
      <h1 class="grid2">
        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>
      </h1>
      <h2 class="grid2">
        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>
      </h2>
      <h3 class="grid2">
        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>
      </h3>
      <h4 class="grid2">
        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>
      </h4>
      <h5 class="grid2">
        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>
      </h5>
      <h6 class="grid2">
        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>
      </h6>
      <p class="grid2">
        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>
      </p>
      <!--//labels-->

      <!--table-->
      <div class="page-header">
        <h3>Tables</h3>
      </div>

      <div class="bs-example" data-example-id="simple-table">
        <table class="table">
          <caption>Optional table caption.</caption>
          <thead>
            <tr>
              <th>#</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Username</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Mark</td>
              <td>Otto</td>
              <td>@mdo</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Jacob</td>
              <td>Thornton</td>
              <td>@fat</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>Larry</td>
              <td>the Bird</td>
              <td>@twitter</td>
            </tr>
          </tbody>
        </table>
      </div><!-- /example -->
      <!--//table-->
      <!--Contextual classes-->
      <div class="page-header">
        <h3>Contextual classes</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <colgroup>
            <col class="col-xs-1">
            <col class="col-xs-7">
          </colgroup>
          <thead>
            <tr>
              <th>Class</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">
                <code>.active</code>
              </th>
              <td>Applies the hover color to a particular row or cell</td>
            </tr>
            <tr>
              <th scope="row">
                <code>.success</code>
              </th>
              <td>Indicates a successful or positive action</td>
            </tr>
            <tr>
              <th scope="row">
                <code>.info</code>
              </th>
              <td>Indicates a neutral informative change or action</td>
            </tr>
            <tr>
              <th scope="row">
                <code>.warning</code>
              </th>
              <td>Indicates a warning that might need attention</td>
            </tr>
            <tr>
              <th scope="row">
                <code>.danger</code>
              </th>
              <td>Indicates a dangerous or potentially negative action</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="bs-example" data-example-id="contextual-table" style="border: 1px solid #eee">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Column heading</th>
              <th>Column heading</th>
              <th>Column heading</th>
            </tr>
          </thead>
          <tbody>
            <tr class="active">
              <th scope="row">1</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr class="success">
              <th scope="row">3</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr>
              <th scope="row">4</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr class="info">
              <th scope="row">5</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr>
              <th scope="row">6</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr class="warning">
              <th scope="row">7</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr>
              <th scope="row">8</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
            <tr class="danger">
              <th scope="row">9</th>
              <td>Column content</td>
              <td>Column content</td>
              <td>Column content</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--//Contextual classes-->
      <!--Forms-->
      <div class="page-header">
        <h3>Forms</h3>
      </div>
      <div class="bs-example" data-example-id="simple-form-inline">
        <form class="form-inline">
          <div class="form-group">
            <label class="sr-only" for="exampleInputEmail3">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Email">
          </div>
          <div class="form-group">
            <label class="sr-only" for="exampleInputPassword3">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox"> Remember me
            </label>
          </div>
          <button type="submit" class="btn btn-default">Sign in</button>
        </form>
      </div>
      <div class="bs-example" data-example-id="simple-horizontal-form">
        <form class="form-horizontal">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
            </div>
          </div>
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
                <label>
                  <input type="checkbox"> Remember me
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Sign in</button>
            </div>
          </div>
        </form>
      </div>
      <div class="bs-example" data-example-id="disabled-fieldset">
        <form>
          <fieldset disabled="">
            <div class="form-group">
              <label for="disabledTextInput">Disabled input</label>
              <input type="text" id="disabledTextInput" class="form-control" placeholder="Disabled input">
            </div>
            <div class="form-group">
              <label for="disabledSelect">Disabled select menu</label>
              <select id="disabledSelect" class="form-control">
                <option>Disabled select</option>
              </select>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox"> Can't check this
              </label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </fieldset>
        </form>
      </div>
      <!--//forms-->
    </div>
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

  <!--//content-->
  <!--//footer-->
  <div class="footer">
    <div class="footer-middle">
      <div class="container">
        <div class="col-md-3 footer-middle-in">
          <a href="index.php"><img src="images/log.png" alt=""></a>
          <p>Suspendisse sed accumsan risus. Curabitur rhoncus, elit vel tincidunt elementum, nunc urna tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor lorem.</p>
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
          <form>
            <input type="text" value="Enter your E-mail" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='Enter your E-mail';}">
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
        <p class="footer-class">&copy; 2016 Shopin. All Rights Reserved | Design by <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
        <div class="clearfix"> </div>
      </div>
    </div>
  </div>
  <!--//footer-->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

  <script src="js/simpleCart.min.js"> </script>
  <!-- slide -->
  <script src="js/bootstrap.min.js"></script>

</body>

</html>