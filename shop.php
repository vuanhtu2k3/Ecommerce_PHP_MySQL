<?php
include('server/connection.php');

// Thiết lập số lượng sản phẩm hiển thị trên mỗi trang
$products_per_page = 8;

// Kiểm tra trang hiện tại, mặc định là trang 1 nếu không có trang nào được chọn
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $products_per_page;

// Kiểm tra nếu có yêu cầu tìm kiếm từ người dùng
if (isset($_POST['search'])) {
    $category = $_POST['category']; // Tên danh mục đã chọn
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];

    // Truy vấn tổng số sản phẩm theo điều kiện tìm kiếm
    $stmt = $conn->prepare('
        SELECT COUNT(*) as total 
        FROM products 
        JOIN category ON products.category_id = category.category_id 
        WHERE category.category_name = ? AND product_price BETWEEN ? AND ?
    ');
    $stmt->bind_param('sii', $category, $min_price, $max_price);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

    // Truy vấn sản phẩm theo trang hiện tại
    $stmt = $conn->prepare('
        SELECT products.* 
        FROM products 
        JOIN category ON products.category_id = category.category_id 
        WHERE category.category_name = ? AND product_price BETWEEN ? AND ? 
        LIMIT ? OFFSET ?
    ');
    $stmt->bind_param('siiii', $category, $min_price, $max_price, $products_per_page, $start_from);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    // Truy vấn tổng số sản phẩm khi không có điều kiện tìm kiếm
    $stmt = $conn->prepare('SELECT COUNT(*) as total FROM products');
    $stmt->execute();
    $result = $stmt->get_result();
    $total_products = $result->fetch_assoc()['total'];

    // Truy vấn sản phẩm khi không có điều kiện tìm kiếm
    $stmt = $conn->prepare('SELECT * FROM products LIMIT ? OFFSET ?');
    $stmt->bind_param('ii', $products_per_page, $start_from);
    $stmt->execute();
    $products = $stmt->get_result();
}

$total_pages = ceil($total_products / $products_per_page); // Tổng số trang dựa trên tổng số sản phẩm
?>

<?php include('layouts/header.php') ?>

<div class="container">
    <div class="row">
        <!-- Search Section (4 Columns) -->
        <div class="col-lg-3 col-md-12 col-sm-12">
            <section id="search" class="my-5 py-5 ms-2">
                <div class="container mt-5 py-5">
                    <p class="text-uppercase fs-3">Search Product</p>
                    <hr class="mx-auto">
                </div>
                <form action="shop.php" method="POST">
                    <div class="row mx-auto container">
                        <div class="row">
                            <!-- Category Section -->
                            <div class="col-lg-12">
                                <p class="text-uppercase fw-bold">Category</p>

                                <div class="form-check">
                                    <input type="radio" value="shoes" class="form-check-input" name="category"
                                        id="category_one">
                                    <label class="form-check-label" for="category_one">Shoes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="coats" class="form-check-input" name="category"
                                        id="category_two">
                                    <label class="form-check-label" for="category_two">Coats</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="shirt" class="form-check-input" name="category"
                                        id="category_three">
                                    <label class="form-check-label" for="category_three">T-shirt</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="watches" class="form-check-input" name="category"
                                        id="category_four">
                                    <label class="form-check-label" for="category_four">Watches</label>
                                </div>
                            </div>

                            <!-- Price Section -->
                            <div class="col-lg-12">
                                <p class="text-uppercase fw-bold">Price Range</p>
                                <input type="range" name="price" value="5000" class="form-range w-100" min="1"
                                    max="1000000" id="priceRange" oninput="updatePriceLabel(this.value)">
                                <div class="w-100">
                                    <span style="float: left;">1</span>
                                    <span style="float: right;">1.000.000 VND</span>
                                </div>
                                <!-- Display the selected price -->
                                <p class="m-4 pt-4 text-uppercase fw-bold">Price: <span id="selectedPrice">5000</span>
                                    VND</p>

                                <!-- Hidden input fields to store the min and max price (for backend usage) -->
                                <input type="hidden" name="min_price" id="minPrice" value="1">
                                <input type="hidden" name="max_price" id="maxPrice" value="1000000">
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-4">
                        <hr class="mx-auto">
                        <input type="submit" name="search" value="Search" class="btn btn-primary">
                    </div>
                </form>
            </section>
        </div>

        <!-- Featured Section (8 Columns) -->
        <div class="col-lg-9 col-md-12 col-sm-12">
            <section id="featured" class="my-5 py-5">
                <div class="container text-center mt-5 py-5">
                    <h3 class="text-uppercase fs-3">Our Featured</h3>
                    <hr class="mx-auto">
                </div>

                <div class="row mx-auto container-fluid">
                    <!-- Products Section -->
                    <?php while ($row = $products->fetch_assoc()) { ?>
                    <div class="product text-center col-lg-3 col-md-6 col-sm-12">
                        <img class="img-fluid mb-3" src="/assets/images/<?php echo $row['product_image'] ?>">
                        <div class="star">
                            <i class="fas fa-star "></i>
                            <i class="fas fa-star "></i>
                            <i class="fas fa-star "></i>
                            <i class="fas fa-star "></i>
                            <i class="fas fa-star "></i>
                        </div>
                        <h3 class="p-product"><?php echo $row['product_name'] ?></h3>
                        <p class="p-price"><?php echo $row['product_price'] ?> VND</p>
                        <a href="single_product.php?product_id=<?php echo $row['product_id'] ?>">
                            <button class="buy-btn p-2 text-uppercase rounded-2 text-white m-3">Buy Now</button>
                        </a>
                    </div>
                    <?php } ?>
                </div>

                <!-- Pagination Section -->
                <nav aria-label="Page navigation example">
                    <ul class="container text-center pagination mt-5">
                        <?php if ($page > 1) : ?>
                        <li class="page-item"><a href="shop.php?page=<?php echo $page - 1; ?>"
                                class="page-link">Previous</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a
                                href="shop.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages) : ?>
                        <li class="page-item"><a href="shop.php?page=<?php echo $page + 1; ?>"
                                class="page-link">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </section>
        </div>
    </div>
</div>

<?php include('layouts/footer.php') ?>

<script>
function updatePriceLabel(value) {
    document.getElementById('selectedPrice').textContent = value;
    document.getElementById('maxPrice').value = value;
}
</script>