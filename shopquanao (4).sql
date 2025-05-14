-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 01:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopquanao`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `note` text NOT NULL,
  `payment_method` enum('cash','credit_card','momo','cad') NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `username`, `email`, `phone`, `address`, `note`, `payment_method`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'haha', 'sdfsdf@gmail.com', '09123456789', 'haha', 'ád', '', 0.00, 'pending', '2025-05-14 10:45:41', '2025-05-14 10:45:41'),
(2, 4, 'haha', 'sdfsdf@gmail.com', '09123456789', 'haha', 'ádasd', '', 20.00, 'pending', '2025-05-14 10:51:34', '2025-05-14 10:51:34'),
(3, 4, 'haha', 'sdfsdf@gmail.com', '09123456789', 'hahaha', 'aaaaa', '', 20.00, 'pending', '2025-05-14 10:52:17', '2025-05-14 10:52:17'),
(4, 4, 'haha', 'sdfsdf@gmail.com', '09123456789', 'hahahahaha', '11111', 'cad', 30.20, 'pending', '2025-05-14 10:59:19', '2025-05-14 10:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quick_overview` text NOT NULL,
  `quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `image`, `price`, `old_price`, `description`, `quick_overview`, `quantity`) VALUES
(1, 'Áo sơ mi xanh lá', 'Women', 'pc.jpg', 10.00, 20.00, 'Áo sơ mi chất liệu cotton, thoáng mát.', 'Ổn1', 100),
(2, 'Áo thun nữ', 'Women', 'pc1.jpg', 14.00, 18.00, 'Quần thun nữ co giãn, thời trang.', 'Ổn2', 100),
(3, 'Áo sọc xanh nam', 'Men', 'pc2.jpg', 20.00, 25.00, 'Áo Nam phong cách, lịch thiệp.', 'Ổn3', 100),
(4, 'Áo khoác nữ', 'Women', 'pc3.jpg', 8.00, 15.00, 'Áo khoác phong cách, trẻ trung.', 'Ổn4', 100),
(5, 'Áo thun nam', 'Men', 'pc4.jpg', 20.00, 30.00, 'Thoải mái, thoáng mát', 'Ổn5', 100),
(6, 'Áo caro nam', 'Men', 'pc5.jpg', 13.00, 18.00, 'Thời trang, phong cách.', 'Ổn6', 100),
(7, 'Áo sơ mi nữ', 'Women', 'pc6.jpg', 17.00, 21.00, 'Áo sơ mi nữ lịch sự.', 'Ổn7', 100),
(8, 'Áo nam công sở', 'Men', 'pc7.jpg', 13.00, 16.00, 'Lịch sự, thoải mái.', 'Ổn8', 100);

-- --------------------------------------------------------

--
-- Table structure for table `product_tabs`
--

CREATE TABLE `product_tabs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `tab_key` varchar(50) NOT NULL,
  `tab_title` varchar(255) NOT NULL,
  `tab_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_tabs`
--

INSERT INTO `product_tabs` (`id`, `product_id`, `tab_key`, `tab_title`, `tab_content`) VALUES
(1, 1, 'tab1', 'Mô tả sản phẩm', '<p>Chiếc áo sơ mi xanh lá này là sự lựa chọn hoàn hảo cho những ngày hè năng động, mang đến vẻ ngoài tươi mới và tràn đầy sức sống. Được làm từ chất liệu cotton cao cấp, áo có khả năng thấm hút mồ hôi tuyệt vời, giữ cho bạn luôn cảm thấy khô ráo và thoải mái suốt cả ngày dài. Sợi vải mềm mại, nhẹ nhàng trên da, phù hợp cả với những làn da nhạy cảm nhất. Kiểu dáng slimfit hiện đại tôn lên vóc dáng, trong khi màu xanh lá cây tự nhiên dễ dàng phối hợp với nhiều loại quần như jeans, kaki hay chinos, tạo nên những bộ trang phục đa dạng từ công sở đến dạo phố cuối tuần. Đây là một item must-have trong tủ đồ của bạn để thể hiện sự cá tính và gu thời trang tinh tế.</p><ul><li> <span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Chất liệu Cotton 100% tự nhiên</li><li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Gam màu Xanh lá tươi mát, bắt mắt</li> <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Thiết kế Slimfit trẻ trung, tôn dáng</li> <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Khả năng thấm hút mồ hôi vượt trội</li> <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Dễ dàng giặt là và bảo quản</li> <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Phù hợp nhiều hoàn cảnh sử dụng</li></ul>'),
(2, 1, 'tab2', 'Thông tin bổ sung', '<p>\r\n    Để chiếc áo sơ mi xanh lá của bạn luôn giữ được màu sắc tươi mới và form dáng chuẩn sau nhiều lần sử dụng, việc tuân thủ các hướng dẫn bảo quản là rất quan trọng. Thông tin bổ sung này cung cấp cho bạn chi tiết về cách giặt, phơi, và bảo quản áo đúng cách, giúp kéo dài tuổi thọ sản phẩm. Ngoài ra, chúng tôi cũng cung cấp bảng quy đổi kích thước chuẩn, hỗ trợ bạn lựa chọn size phù hợp nhất với cơ thể mình, đảm bảo sự thoải mái và vừa vặn tối ưu. Vui lòng đọc kỹ các thông tin dưới đây để có trải nghiệm tốt nhất với sản phẩm.\r\n</p>\r\n<ul>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Hướng dẫn giặt máy/tay chi tiết</li>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Thông tin size số và cách chọn size</li>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Xuất xứ sản phẩm và thông tin nhãn hàng</li>\r\n</ul>\r\n'),
(3, 1, 'tab3', 'Đánh giá', '<p>\r\n    Chúng tôi rất vui mừng nhận được nhiều phản hồi tích cực từ khách hàng về chiếc áo sơ mi xanh lá này. Đa số đều đánh giá cao chất lượng sản phẩm, từ chất liệu vải đến đường may tỉ mỉ. Khách hàng đặc biệt yêu thích màu sắc tươi sáng và cảm giác thoải mái khi mặc. Những đánh giá này là động lực lớn để chúng tôi tiếp tục mang đến những sản phẩm tốt nhất. Nếu bạn đã trải nghiệm sản phẩm, hãy chia sẻ cảm nhận của mình để giúp những người mua khác đưa ra quyết định và giúp chúng tôi cải thiện hơn nữa nhé! Sự hài lòng của bạn là ưu tiên hàng đầu của chúng tôi.\r\n</p>\r\n<ul>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Chất vải mềm mại, thoáng khí tuyệt vời</li>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Màu xanh lá tươi sáng, không bị phai</li>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Form áo chuẩn, vừa vặn và tôn dáng</li>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Cảm giác thoải mái khi mặc suốt cả ngày dài</li>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Nhận được rất nhiều lời khen từ bạn bè</li>\r\n    <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Chất lượng xứng đáng với giá tiền bỏ ra</li>\r\n</ul>\r\n'),
(4, 2, 'tab1', 'Mô tả sản phẩm', '<p>Áo thun nữ với chất liệu co giãn 4 chiều, thấm hút mồ hôi tốt.</p><p>Thiết kế đơn giản nhưng thời trang, dễ dàng phối đồ.</p><ul><li>Chất liệu: Cotton Spandex</li><li>Kiểu dáng: Áo thun cổ tròn</li><li>Màu sắc: Đa dạng</li><li>Kích thước: S, M, L, XL</li></ul>'),
(5, 2, 'tab2', 'Thông tin bổ sung', '<p>Sản phẩm lý tưởng cho các hoạt động thể thao hoặc mặc hàng ngày.</p><p>Giặt tay hoặc giặt máy nhẹ nhàng.</p>'),
(6, 2, 'tab3', 'Đánh giá', '<p>Áo mềm mại, mặc rất thoải mái.</p><p>Giao hàng nhanh, sản phẩm đúng mô tả.</p>'),
(7, 3, 'tab1', 'Mô tả sản phẩm', '<p>Áo sọc ngang phong cách trẻ trung, hiện đại dành cho nam.</p><p>Chất vải mềm mại, không bai nhão sau khi giặt.</p><ul><li>Chất liệu: Vải tổng hợp cao cấp</li><li>Kiểu dáng: Áo thun sọc ngang</li><li>Màu sắc: Xanh phối trắng</li><li>Phù hợp: Mặc đi chơi, dạo phố</li></ul>'),
(8, 3, 'tab2', 'Thông tin bổ sung', '<p>Kết hợp dễ dàng với quần jeans hoặc quần short.</p><p>Giặt riêng với quần áo màu sáng.</p>'),
(9, 3, 'tab3', 'Đánh giá', '<p>Áo đẹp hơn mong đợi, màu sắc tươi tắn.</p><p>Mình rất thích chiếc áo này.</p>'),
(10, 4, 'tab1', 'Mô tả sản phẩm', '<p>Áo khoác nhẹ nhàng, kiểu dáng năng động.</p><p>Chất liệu bền đẹp, giữ ấm vừa phải.</p><ul><li>Chất liệu: Polyester</li><li>Kiểu dáng: Áo khoác bomber</li><li>Màu sắc: Hồng, Đen, Xám</li><li>Size: Freesize</li></ul>'),
(11, 4, 'tab2', 'Thông tin bổ sung', '<p>Thích hợp mặc vào những ngày se lạnh hoặc che nắng.</p><p>Có túi tiện lợi.</p>'),
(12, 4, 'tab3', 'Đánh giá', '<p>Áo khoác xinh xắn, giá cả hợp lý.</p><p>Mặc rất thoải mái và ấm áp.</p>'),
(13, 5, 'tab1', 'Mô tả sản phẩm', '<p>Áo thun trơn basic không thể thiếu trong tủ đồ của phái mạnh.</p><p>Chất vải thoáng mát, thấm hút mồ hôi tốt.</p><ul><li>Chất liệu: Cotton 100%</li><li>Kiểu dáng: Áo thun cổ tròn</li><li>Màu sắc: Đen, Trắng, Xám, Xanh navy</li><li>Phù hợp: Mặc hàng ngày</li></ul>'),
(14, 5, 'tab2', 'Thông tin bổ sung', '<p>Dễ dàng phối với mọi loại trang phục.</p><p>Độ bền cao, giữ form tốt.</p>'),
(15, 5, 'tab3', 'Đánh giá', '<p>Áo chất lượng tốt, mặc rất thích.</p><p>Sẽ mua thêm màu khác.</p>'),
(16, 6, 'tab1', 'Mô tả sản phẩm', '<p>Áo sơ mi caro phong cách lịch lãm, cá tính.</p><p>Chất liệu vải mềm mại, ít nhăn.</p><ul><li>Chất liệu: Cotton pha</li><li>Kiểu dáng: Áo sơ mi dài tay</li><li>Họa tiết: Caro</li><li>Phù hợp: Đi làm, đi chơi</li></ul>'),
(17, 6, 'tab2', 'Thông tin bổ sung', '<p>Có thể mặc đơn hoặc phối với áo thun bên trong.</p><p>Dễ dàng giặt ủi.</p>'),
(18, 6, 'tab3', 'Đánh giá', '<p>Áo đẹp, form vừa vặn.</p><p>Tôi rất hài lòng với sản phẩm này.</p>'),
(19, 7, 'tab1', 'Mô tả sản phẩm', '<p>Áo sơ mi nữ kiểu dáng thanh lịch, trang nhã.</p><p>Chất liệu mềm rủ, tạo cảm giác nhẹ nhàng khi mặc.</p><ul><li>Chất liệu: Lụa hoặc voan</li><li>Kiểu dáng: Sơ mi cách điệu</li><li>Màu sắc: Pastel</li><li>Phù hợp: Môi trường công sở</li></ul>'),
(20, 7, 'tab2', 'Thông tin bổ sung', '<p>Kết hợp tuyệt vời với chân váy hoặc quần tây.</p><p>Nên giặt tay để giữ form áo.</p>'),
(21, 7, 'tab3', 'Đánh giá', '<p>Áo rất xinh, mặc đi làm ai cũng khen.</p><p>Chất vải mát, không bí.</p>'),
(22, 8, 'tab1', 'Mô tả sản phẩm', '<p>Áo sơ mi nam công sở form slimfit tôn dáng.</p><p>Chất liệu cao cấp, thấm hút mồ hôi tốt.</p><ul><li>Chất liệu: Cotton pha Spandex</li><li>Kiểu dáng: Sơ mi dài tay công sở</li><li>Màu sắc: Xanh dương, Trắng</li><li>Phù hợp: Môi trường văn phòng</li></ul>'),
(23, 8, 'tab2', 'Thông tin bổ sung', '<p>Dễ dàng là ủi, ít nhăn.</p><p>Mang đến vẻ ngoài chuyên nghiệp.</p>'),
(24, 8, 'tab3', 'Đánh giá', '<p>Áo mặc rất thoải mái khi làm việc.</p><p>Form áo đẹp, giá hợp lý.</p>'),
(25, 2, 'tab1', 'Product Description', '<p>Áo thun nữ được thiết kế với kiểu dáng basic, trẻ trung và hiện đại. Sản phẩm sử dụng chất liệu cotton co giãn 4 chiều, thoáng mát, thấm hút mồ hôi tốt – phù hợp với khí hậu nóng ẩm tại Việt Nam. Phù hợp để mặc đi chơi, ở nhà, dạo phố, đi học,...</p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Thiết kế form suông, ôm nhẹ</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Logo thêu tinh tế phía ngực trái</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Đường may sắc nét, tỉ mỉ</li>\r\n</ul>'),
(26, 2, 'tab2', 'Additional Information', '<p><strong>Thông số kỹ thuật:</strong></p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất liệu: 95% cotton, 5% spandex</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Màu sắc: Trắng, đen, hồng, tím pastel</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Kích thước: S, M, L, XL</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Trọng lượng: 200g</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Hướng dẫn giặt: Giặt tay hoặc giặt máy với nước lạnh</li>\r\n</ul>'),
(27, 2, 'tab3', 'Reviews', '<p><strong>Khách hàng nói gì về sản phẩm:</strong></p>\r\n<ul>\r\n  <li><em>Ngọc Anh – Hà Nội:</em> Áo đẹp, mặc mát, đúng màu pastel đang hot!</li>\r\n  <li><em>Phương Trang – TP.HCM:</em> Chất vải mịn, không bị xù sau khi giặt máy.</li>\r\n  <li><em>Trung Tâm CSKH:</em> Sản phẩm được đánh giá 4.8/5 từ 139 lượt mua.</li>\r\n</ul>'),
(28, 3, 'tab1', 'Product Description', '<p>Áo sơ mi sọc xanh dành cho nam giới mang phong cách lịch thiệp, hiện đại. Phù hợp trong môi trường công sở lẫn dạo phố. Sọc nhỏ màu xanh trắng tạo cảm giác thanh thoát, dễ phối cùng quần tây hoặc jeans.</p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Form regular-fit, dễ mặc</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Cổ bẻ cứng, lịch sự</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Cài khuy trước tiện lợi</li>\r\n</ul>'),
(29, 3, 'tab2', 'Additional Information', '<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất liệu: Vải kate cao cấp</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Màu sắc: Sọc xanh trắng</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Kích thước: M, L, XL, XXL</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Khối lượng: 250g</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Giặt máy chế độ nhẹ, ủi ở nhiệt độ thấp</li>\r\n</ul>'),
(30, 3, 'tab3', 'Reviews', '<ul>\r\n  <li><em>Đức Huy – Đà Nẵng:</em> Áo mát, vải không bị nhăn, mặc đi làm rất hợp.</li>\r\n  <li><em>Quốc Bảo – Cần Thơ:</em> Dáng hơi rộng so với size, bạn nào ốm nên chọn nhỏ hơn 1 size.</li>\r\n</ul>'),
(31, 4, 'tab1', 'Product Description', '<p>Áo khoác nữ kiểu dáng bomber thời trang, trẻ trung. Phù hợp khi đi chơi, dạo phố, hay đi học vào buổi sáng sớm hoặc chiều tối. Chất vải dày dặn nhưng vẫn thoáng khí, thích hợp mùa thu – đông nhẹ.</p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Bo tay và bo gấu co giãn tốt</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Khóa kéo mượt, chắc chắn</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Có túi 2 bên</li>\r\n</ul>'),
(32, 4, 'tab2', 'Additional Information', '<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất liệu: Nỉ Hàn Quốc</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Màu sắc: Xám, hồng phấn, xanh mint</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Size: Free size (50–58kg)</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Khối lượng: 500g</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Bên trong có lớp lót mỏng</li>\r\n</ul>'),
(33, 4, 'tab3', 'Reviews', '<ul>\r\n  <li><em>Thu Hương – Hải Phòng:</em> Rất xinh, mặc lên nhìn cute cực!</li>\r\n  <li><em>Khánh Chi – Bình Dương:</em> Giá hơi cao chút nhưng đáng tiền.</li>\r\n</ul>'),
(34, 5, 'tab1', 'Product Description', '<p>Chiếc áo thun nam phong cách thể thao, năng động. Vải cotton co giãn giúp thấm hút mồ hôi tốt, thích hợp mặc thường ngày, chạy bộ hoặc tập gym.</p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Logo in phản quang</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Cổ tròn truyền thống</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Thấm hút mồ hôi tốt</li>\r\n</ul>'),
(35, 5, 'tab2', 'Additional Information', '<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất liệu: Cotton 100%</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Màu sắc: Trắng, đen, xám lông chuột</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Kích thước: S, M, L, XL</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Giặt máy thoải mái</li>\r\n</ul>'),
(36, 5, 'tab3', 'Reviews', '<ul>\r\n  <li><em>Thái Sơn – Đồng Nai:</em> Mặc đi chơi lẫn ở nhà đều ổn.</li>\r\n  <li><em>Minh Khoa – Đắk Lắk:</em> Giá hợp lý, mình mua combo 3 cái.</li>\r\n</ul>'),
(37, 6, 'tab1', 'Product Description', '<p>Áo sơ mi caro nam thiết kế đơn giản, tinh tế. Kiểu dáng slim-fit nhẹ giúp tôn dáng mà không quá ôm. Phù hợp cho cả đi học, đi chơi hoặc làm việc tự do.</p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Khuy cài cổ kín</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Sọc caro phối xanh/đen đẹp mắt</li>\r\n</ul>'),
(38, 6, 'tab2', 'Additional Information', '<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất liệu: Vải flannel dày</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Size: M, L, XL</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Màu sắc: Caro đỏ đen, xanh trắng</li>\r\n</ul>'),
(39, 6, 'tab3', 'Reviews', '<ul>\r\n  <li><em>Hải – Bắc Ninh:</em> Chất flannel ấm, nhưng không quá nóng, vừa phải.</li>\r\n</ul>'),
(40, 7, 'tab1', 'Product Description', '<p>Áo sơ mi nữ thanh lịch, thiết kế hiện đại, có thể phối cùng chân váy hoặc quần tây. Chất liệu chống nhăn, phù hợp mặc đi làm hoặc gặp khách hàng.</p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Cổ đức gọn gàng</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Cài khuy tay, phối viền</li>\r\n</ul>'),
(41, 7, 'tab2', 'Additional Information', '<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất liệu: Cotton tổng hợp</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Size: S, M, L</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Màu: Trắng, xanh pastel, be</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Không bị bai nhão sau giặt</li>\r\n</ul>'),
(42, 7, 'tab3', 'Reviews', '<ul>\r\n  <li><em>Trà My – Huế:</em> Mặc đi làm cả tuần, mỗi ngày mix 1 kiểu khác nhau.</li>\r\n</ul>'),
(43, 8, 'tab1', 'Product Description', '<p>Áo sơ mi nam công sở, dáng regular fit, phù hợp với dân văn phòng. Thiết kế đơn giản, lịch lãm, dễ phối với quần âu hoặc jean. Màu sắc nhã nhặn, mang lại vẻ chuyên nghiệp cho người mặc.</p>\r\n<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Cổ áo dáng đứng, không bị bè</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Logo thêu ẩn tinh tế</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất vải thoáng, không hầm nóng</li>\r\n</ul>'),
(44, 8, 'tab2', 'Additional Information', '<ul>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Chất liệu: Cotton lạnh</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Size: M, L, XL</li>\r\n  <li><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>Màu sắc: Trắng ngà, xanh pastel, xám nhạt</li>\r\n</ul>'),
(45, 8, 'tab3', 'Reviews', '<ul>\r\n  <li><em>Long – Nha Trang:</em> Rất hài lòng. Vừa vặn, cắt may đẹp, giao hàng nhanh.</li>\r\n  <li><em>Dũng – Hà Nội:</em> Mặc đi họp hay gặp khách đều ổn áp.</li>\r\n</ul>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `cash` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `cash`) VALUES
(4, 'haha', '$2y$10$JQGfC12HwPu.F1FDab6gOu5icCbpf/o8nHRc10GDcYM3dUBQ.RxO6', 'sdfsdf@gmail.com', '09123456789', 245);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_tabs`
--
ALTER TABLE `product_tabs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_tabs`
--
ALTER TABLE `product_tabs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_tabs`
--
ALTER TABLE `product_tabs`
  ADD CONSTRAINT `product_tabs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
