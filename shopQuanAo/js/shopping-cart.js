var check = false;

function changeVal(el) {
  var qt = parseFloat(el.parent().children(".qt").html());
  var price = parseFloat(el.parent().children(".price").html());
  var eq = Math.round(price * qt * 100) / 100;

  el.parent().children(".full-price").html(eq + "€");

  changeTotal();
}

function changeTotal() {
  var price = 0;

  $(".full-price").each(function (index) {
    price += parseFloat($(".full-price").eq(index).html().replace('€', ''));
  });

  price = Math.round(price * 100) / 100;
  var tax = Math.round(price * 0.05 * 100) / 100;
  var shipping = parseFloat($(".shipping span").html());
  var fullPrice = Math.round((price + tax + shipping) * 100) / 100;

  if (price == 0) {
    fullPrice = 0;
  }

  $(".subtotal span").html(price.toFixed(2));
  $(".tax span").html(tax.toFixed(2));
  $(".total span").html(fullPrice.toFixed(2));

  // Cập nhật tổng tiền trong quickbeam cart
  $('#quick-cart-price').text(price.toFixed(2));
}

$(document).ready(function () {

  $(".remove").click(function () {
    var el = $(this);
    var productId = el.data('id');
    var productElement = el.closest('.product');
    var price = parseFloat(productElement.find('.price').text().replace('€', ''));
    var quantity = parseInt(productElement.find('.qt').text());
    var totalPrice = price * quantity;

    // Gửi yêu cầu xóa sản phẩm đến server
    $.ajax({
      url: '/shopQuanAo/php/cart.php',
      method: 'POST',
      data: {
        product_id: productId,
        quantity: 0,
        action: 'remove'
      },
      success: function (response) {
        if (response.success) {
          // Xóa sản phẩm khỏi cart chính
          productElement.addClass("removed");
          window.setTimeout(
            function () {
              productElement.slideUp('fast', function () {
                productElement.remove();
                if ($(".product").length == 0) {
                  if (check) {
                    $("#cart").html("<h1>The shop does not function, yet!</h1><p>If you liked my shopping cart, please take a second and heart this Pen on <a href='https://codepen.io/ziga-miklic/pen/xhpob'>CodePen</a>. Thank you!</p>");
                  } else {
                    $("#cart").html("<h1>No products!</h1>");
                  }
                }
                changeTotal();
              });
            }, 200);

          // Xóa sản phẩm khỏi quickbeam cart nếu có
          var quickbeamProduct = $('.quickbeam-cart .product[data-id="' + productId + '"]');
          if (quickbeamProduct.length > 0) {
            quickbeamProduct.fadeOut(300, function () {
              $(this).remove();
              // Cập nhật tổng tiền trong quickbeam cart
              var currentTotal = parseFloat($('#quick-cart-price').text());
              var newTotal = currentTotal - totalPrice;
              $('#quick-cart-price').text(newTotal.toFixed(2));

              // Kiểm tra nếu không còn sản phẩm nào trong quickbeam cart
              if ($('.quickbeam-cart .product').length === 0) {
                $('#quick-cart-price').text('0');
                $('.quickbeam-cart').hide();
              }
            });
          }
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function () {
        alert('There was an error processing your request. Please try again.');
      }
    });
  });

  $(".qt-plus").click(function () {
    $(this).parent().children(".qt").html(parseInt($(this).parent().children(".qt").html()) + 1);

    $(this).parent().children(".full-price").addClass("added");

    var el = $(this);
    window.setTimeout(function () { el.parent().children(".full-price").removeClass("added"); changeVal(el); }, 150);
  });

  $(".qt-minus").click(function () {

    child = $(this).parent().children(".qt");

    if (parseInt(child.html()) > 1) {
      child.html(parseInt(child.html()) - 1);
    }

    $(this).parent().children(".full-price").addClass("minused");

    var el = $(this);
    window.setTimeout(function () { el.parent().children(".full-price").removeClass("minused"); changeVal(el); }, 150);
  });

  window.setTimeout(function () { $(".is-open").removeClass("is-open") }, 1200);

  $(".btn").click(function () {
    check = true;
    $(".remove").click();
  });

  // Xử lý sự kiện khi nhấn nút remove trong quickbeam cart
  $('.quickbeam-cart .remove').on('click', function () {
    var productId = $(this).data('id');
    var productElement = $(this).closest('.product');
    var quantity = parseInt(productElement.find('.qt').text());

    // Gửi yêu cầu cập nhật số lượng đến server
    $.ajax({
      url: '/shopQuanAo/php/cart.php',
      method: 'POST',
      data: {
        product_id: productId,
        quantity: -1,
        action: 'update'
      },
      success: function (response) {
        if (response.success) {
          if (response.quantity === 0) {
            // Xóa sản phẩm khỏi quickbeam cart
            productElement.fadeOut(300, function () {
              $(this).remove();

              // Cập nhật tổng tiền từ cart chính
              updateQuickbeamPrice();

              // Kiểm tra nếu không còn sản phẩm nào
              if ($('.quickbeam-cart .product').length === 0) {
                $('#quick-cart-price').text('0');
                $('.quickbeam-cart').hide();
              }
            });

            // Xóa sản phẩm khỏi cart chính
            var mainProduct = $('.product[data-id="' + productId + '"]');
            if (mainProduct.length > 0) {
              mainProduct.fadeOut(300, function () {
                $(this).remove();
                changeTotal();

                if ($('.product').length === 0) {
                  $("#cart").html("<h1>No products!</h1>");
                }
              });
            }
          } else {
            // Cập nhật số lượng hiển thị
            productElement.find('.qt').text(response.quantity);
            // Cập nhật tổng tiền từ cart chính
            updateQuickbeamPrice();
          }
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function () {
        alert('There was an error processing your request. Please try again.');
      }
    });
  });

  // Xử lý sự kiện khi nhấn nút tăng số lượng
  $('.qt-plus').on('click', function () {
    var productId = $(this).data('id');
    var qtElement = $(this).siblings('.qt');
    var quantity = parseInt(qtElement.text());

    // Gửi yêu cầu cập nhật số lượng đến server
    $.ajax({
      url: '/shopQuanAo/php/cart.php',
      method: 'POST',
      data: {
        product_id: productId,
        quantity: 1
      },
      success: function (response) {
        if (response.success) {
          // Cập nhật số lượng hiển thị
          qtElement.text(response.quantity);
          // Cập nhật giá
          updateProductPrice($(this).closest('.product'), response.quantity);
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function () {
        alert('There was an error processing your request. Please try again.');
      }
    });
  });

  // Xử lý sự kiện khi nhấn nút giảm số lượng
  $('.qt-minus').on('click', function () {
    var productId = $(this).data('id');
    var qtElement = $(this).siblings('.qt');
    var quantity = parseInt(qtElement.text());

    $.ajax({
      url: '/shopQuanAo/php/cart.php',
      method: 'POST',
      data: {
        product_id: productId,
        quantity: -1
      },
      success: function (response) {
        if (response.success) {
          // Cập nhật số lượng hiển thị
          qtElement.text(response.quantity);
          // Cập nhật giá
          updateProductPrice($(this).closest('.product'), response.quantity);
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function () {
        alert('There was an error processing your request. Please try again.');
      }
    });
  });

  // Hàm cập nhật giá của một sản phẩm
  function updateProductPrice(productElement, quantity) {
    // Lấy giá đơn vị
    var unitPrice = parseFloat(productElement.find('.price').text().replace('€', ''));
    // Tính giá mới dựa trên số lượng
    var newPrice = (unitPrice * quantity).toFixed(2);
    // Cập nhật hiển thị tổng giá
    productElement.find('.full-price').text(newPrice + "€");

    // Cập nhật tổng tiền
    updateTotals();
  }

  // Cập nhật hàm updateTotals để đồng bộ với quickbeam cart
  function updateTotals() {
    var subtotal = 0;

    // Tính tổng giá trị giỏ hàng
    $('.full-price').each(function () {
      subtotal += parseFloat($(this).text().replace('€', ''));
    });

    // Tính thuế và tổng cộng
    var tax = subtotal * 0.05;
    var shipping = subtotal > 0 ? 5 : 0;
    var total = subtotal + tax + shipping;

    // Cập nhật hiển thị
    $('.subtotal span').text(subtotal.toFixed(2));
    $('.tax span').text(tax.toFixed(2));
    $('.shipping span').text(shipping.toFixed(2));
    $('.total span').text(total.toFixed(2));

    // Cập nhật tổng tiền trong quickbeam cart
    $('#quick-cart-price').text(subtotal.toFixed(2));
  }

  // Hàm cập nhật giá trong quickbeam cart từ cart chính
  function updateQuickbeamPrice() {
    var total = parseFloat($('.total span').text().replace('$', ''));
    $('#quick-cart-price').text(total.toFixed(2));
  }
});