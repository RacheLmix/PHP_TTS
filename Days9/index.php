<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TechFactory Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            margin: 0;
            padding: 50px;
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #2a9d8f;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .button:hover {
            transform: scale(1.08);
            background-color: #21867a;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <h1>Welcome to TechFactory Backend</h1>

    <ul id="nav-links">
        <li><a class="button" href="products.php">Quản lý sản phẩm</a></li>
        <li><a class="button" href="orders.php">Quản lý đơn hàng</a></li>
        <li><a class="button" href="order_items.php?order_id=1">Thêm sản phẩm vào đơn hàng</a></li>
        <li><a class="button" href="order_totals.php">Tổng tiền từng đơn hàng</a></li>
    </ul>

    <!-- Thêm thư viện Anime.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script>
        // Animation cho từng button khi load trang
        anime({
            targets: '#nav-links .button',
            opacity: [0, 1],
            translateY: [40, 0],
            delay: anime.stagger(200), // delay từng thằng
            duration: 800,
            easing: 'easeOutBack'
        });
    </script>

</body>
</html>
