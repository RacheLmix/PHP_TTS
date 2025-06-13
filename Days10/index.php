<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce AJAX</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>E-commerce AJAX</h1>

    <!-- 3.1 AJAX Intro – Lấy chi tiết sản phẩm theo ID -->
    <div class="product-list">
        <h2>Danh sách sản phẩm</h2>
        <ul id="products">
            <?php
            include 'config.php';
            $sql = "SELECT id, name FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li class='product-item' data-id='" . $row["id"] . "'>" . $row["name"] . "</li>";
                }
            } else {
                echo "<li>Không có sản phẩm nào.</li>";
            }
            $conn->close();
            ?>
        </ul>
    </div>

    <div class="product-detail">
        <h2>Chi tiết sản phẩm</h2>
        <div id="product-detail-content">
            <p>Chọn một sản phẩm từ danh sách để xem chi tiết.</p>
        </div>
    </div>

    <!-- 3.2 AJAX PHP – Thêm sản phẩm vào giỏ hàng -->
    <div class="cart-section">
        <h2>Giỏ hàng</h2>
        <p>Sản phẩm trong giỏ: <span id="cart-count">0</span></p>
        <button id="add-to-cart-btn" style="display: none;">Thêm vào giỏ</button>
    </div>

    <!-- 3.3 AJAX Database – Hiển thị đánh giá (review) sản phẩm -->
    <div class="reviews-section">
        <h2>Đánh giá sản phẩm</h2>
        <button id="load-reviews-btn" style="display: none;">Xem đánh giá</button>
        <div id="reviews-content"></div>

        <h3>Gửi đánh giá của bạn</h3>
        <form id="review-form" style="display: none;" enctype="multipart/form-data">
            <input type="hidden" id="review-product-id" name="product_id">
            <p>
                <label for="reviewer-username">Tên của bạn:</label><br>
                <input type="text" id="reviewer-username" name="username" required>
            </p>
            <p>
                <label for="review-rating">Đánh giá (1-5):</label><br>
                <input type="number" id="review-rating" name="rating" min="1" max="5" required>
            </p>
            <p>
                <label for="review-comment">Bình luận:</label><br>
                <textarea id="review-comment" name="comment" rows="4" cols="50" required></textarea>

<label for="review-image">Ảnh (tùy chọn):</label><br>
<input type="file" id="review-image" name="review_image" accept="image/*">
            </p>
            <button type="submit">Gửi đánh giá</button>
        </form>
    </div>

    <!-- 3.4 AJAX XML – Lấy danh sách thương hiệu từ file XML -->
    <div class="brand-section">
        <h2>Thương hiệu theo ngành hàng</h2>
        <label for="category-select">Chọn ngành hàng:</label>
        <select id="category-select">
            <option value="">-- Chọn --</option>
            <option value="Điện tử">Điện tử</option>
            <option value="Thời trang">Thời trang</option>
            <option value="Gia dụng">Gia dụng</option>
        </select>
        <div id="brands-content">
            <p>Thương hiệu sẽ hiển thị ở đây.</p>
        </div>
    </div>

    <!-- New: 3.4.1 AJAX – Hiển thị sản phẩm theo thương hiệu -->
    <div class="products-by-brand-section">
        <h2>Sản phẩm theo thương hiệu</h2>
        <div id="products-by-brand-content">
            <p>Chọn một thương hiệu để xem sản phẩm.</p>
        </div>
    </div>

    <!-- 3.5 AJAX Live Search – Tìm kiếm sản phẩm theo thời gian thực -->
    <div class="search-section">
        <h2>Tìm kiếm sản phẩm</h2>
        <input type="text" id="search-input" placeholder="Nhập tên sản phẩm...">
        <div id="search-results"></div>
    </div>

    <!-- 3.6 AJAX Poll – Bình chọn tính năng cần cải thiện trên trang -->
    <div class="poll-section">
        <h2>Bình chọn</h2>
        <p>Bạn muốn cải thiện điều gì trên website?</p>
        <form id="poll-form">
            <label class="poll-option"><input type="radio" name="poll_option" value="Giao diện"> Giao diện</label>
            <label class="poll-option"><input type="radio" name="poll_option" value="Tốc độ"> Tốc độ</label>
            <label class="poll-option"><input type="radio" name="poll_option" value="Dịch vụ khách hàng"> Dịch vụ khách hàng</label><br><br>
            <button type="submit">Gửi bình chọn</button>
        </form>
        <div id="poll-results"></div>
    </div>

    <script>
        // 3.1 AJAX Intro – Lấy chi tiết sản phẩm theo ID
        document.querySelectorAll('.product-item').forEach(item => {
            item.addEventListener('click', function() {
                const productId = this.dataset.id;
                fetch('get_product_detail.php?id=' + productId)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('product-detail-content').innerHTML = data;
                        document.getElementById('add-to-cart-btn').style.display = 'block';
                        document.getElementById('add-to-cart-btn').dataset.productId = productId;
                        document.getElementById('load-reviews-btn').style.display = 'block';
                        document.getElementById('load-reviews-btn').dataset.productId = productId;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        // 3.2 AJAX PHP – Thêm sản phẩm vào giỏ hàng
        document.getElementById('add-to-cart-btn').addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.cartCount;
                    alert('Sản phẩm đã được thêm vào giỏ hàng!');
                } else {
                    alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // 3.3 AJAX Database – Hiển thị đánh giá (review) sản phẩm
        document.getElementById('load-reviews-btn').addEventListener('click', function() {
            const productId = this.dataset.productId;
            document.getElementById('review-product-id').value = productId; // Set product ID for review form
            document.getElementById('review-form').style.display = 'block'; // Show review form
            fetch('get_reviews.php?product_id=' + productId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('reviews-content').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });

        // New: 3.3 AJAX Database – Gửi đánh giá (review) sản phẩm
        document.getElementById('review-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đánh giá của bạn đã được gửi thành công!');
                    // Optionally clear form and reload reviews
                    document.getElementById('reviewer-username').value = '';
                    document.getElementById('review-rating').value = '';
                    document.getElementById('review-comment').value = '';
document.getElementById('review-image').value = '';
                    document.getElementById('load-reviews-btn').click(); // Reload reviews
                } else {
                    alert('Có lỗi xảy ra khi gửi đánh giá: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // 3.4 AJAX XML – Lấy danh sách thương hiệu từ file XML
        document.getElementById('category-select').addEventListener('change', function() {
            const category = this.value;
            if (category) {
                fetch('get_brands.php?category=' + category)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('brands-content').innerHTML = data;
                        document.getElementById('products-by-brand-content').innerHTML = '<p>Chọn một thương hiệu để xem sản phẩm.</p>'; // Clear previous products

                        // Add event listeners to newly loaded brand items
                        const brandItems = document.querySelectorAll('.brand-item');
                        brandItems.forEach(item => {
                            item.addEventListener('click', function() {
                                const brandName = this.dataset.brandName;
                                fetch('get_products_by_brand.php?brand=' + encodeURIComponent(brandName))
                                    .then(response => response.text())
                                    .then(productData => {
                                        document.getElementById('products-by-brand-content').innerHTML = productData;
                                    })
                                    .catch(error => console.error('Error fetching products by brand:', error));
                            });
                        });

                        // Automatically click the first brand item if available
                        if (brandItems.length > 0) {
                            brandItems[0].click();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                document.getElementById('brands-content').innerHTML = '<p>Thương hiệu sẽ hiển thị ở đây.</p>';
                document.getElementById('products-by-brand-content').innerHTML = '<p>Chọn một thương hiệu để xem sản phẩm.</p>';
            }
        });

        // 3.5 AJAX Live Search – Tìm kiếm sản phẩm theo thời gian thực
        let searchTimeout;
        document.getElementById('search-input').addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            searchTimeout = setTimeout(() => {
                if (query.length > 2) { // Only search if query is at least 3 characters long
                    fetch('search_products.php?query=' + encodeURIComponent(query))
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('search-results').innerHTML = data;
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    document.getElementById('search-results').innerHTML = '';
                }
            }, 300); // Debounce for 300ms
        });

        // 3.6 AJAX Poll – Bình chọn tính năng cần cải thiện trên trang
        document.getElementById('poll-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const selectedOption = document.querySelector('input[name="poll_option"]:checked');
            if (selectedOption) {
                fetch('submit_poll.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'option=' + encodeURIComponent(selectedOption.value)
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('poll-results').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
            } else {
                alert('Vui lòng chọn một lựa chọn để bình chọn.');
            }
        });

        // Initial cart count (can be loaded via AJAX on page load if needed)
        // For simplicity, we'll assume 0 initially and update on add to cart.
    </script>
</body>
</html>