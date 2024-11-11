<?php
include('../server/connection.php');

// Xử lý khi có yêu cầu GET với product_id hoặc POST để cập nhật sản phẩm
if (isset($_GET['product_id']) || isset($_POST['update_product'])) {
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        // Lấy thông tin sản phẩm khi có product_id
        $stmt = $conn->prepare('SELECT * FROM products
                                INNER JOIN category ON products.category_id = category.category_id
                                WHERE product_id = ?');
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $products = $stmt->get_result();

        // Lấy danh sách các danh mục cho dropdown
        $stmt2 = $conn->prepare('SELECT * FROM category');
        $stmt2->execute();
        $categories = $stmt2->get_result();
    } elseif (isset($_POST['update_product'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $product_color = $_POST['product_color'];
        $product_category = $_POST['product_category'];

        // Mảng chứa các trường hình ảnh
        $imageFields = ['product_image', 'product_image2', 'product_image3', 'product_image4'];
        $uploadedImages = [];

        // Duyệt từng trường ảnh để xử lý tải lên và cập nhật
        foreach ($imageFields as $imageField) {
            if (!empty($_FILES[$imageField]['name'])) {
                $imageName = $_FILES[$imageField]['name'];
                $imageTmpName = $_FILES[$imageField]['tmp_name'];
                $imagePath = '../assets/images/' . $imageName;

                // Di chuyển tệp hình ảnh lên thư mục đích
                if (move_uploaded_file($imageTmpName, $imagePath)) {
                    $uploadedImages[$imageField] = $imageName;
                }
            } else {
                // Giữ nguyên hình ảnh cũ nếu không có hình mới
                $uploadedImages[$imageField] = $_POST['existing_' . $imageField];
            }
        }

        // Cập nhật sản phẩm với thông tin và hình ảnh
        $stmt1 = $conn->prepare('UPDATE products 
                                 SET product_name = ?, product_price = ?, product_description = ?, product_color = ?, category_id = ?, product_image = ?, product_image2 = ?, product_image3 = ?, product_image4 = ?
                                 WHERE product_id = ?');
        $stmt1->bind_param(
            'sdssissssi',
            $product_name,
            $product_price,
            $product_description,
            $product_color,
            $product_category,
            $uploadedImages['product_image'],
            $uploadedImages['product_image2'],
            $uploadedImages['product_image3'],
            $uploadedImages['product_image4'],
            $product_id
        );

        if ($stmt1->execute()) {
            header('location: list_products.php?message=Products updated successfully');
            exit(); // Đảm bảo ngừng thực thi script sau khi redirect
        } else {
            header('location: list_products.php?error=Error updating products');
            exit();
        }
    }
}
?>

<?php include('../admin/layouts/app.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="list_products.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <form action="edit_products.php" method="POST" enctype="multipart/form-data">
            <div class="container-fluid">
                <?php if (isset($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name">Product Name</label>
                                            <input type="text" name="product_name" id="product_name" class="form-control"
                                                placeholder="Name"
                                                value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category">Product Category</label>
                                            <select name="product_category" id="product_category" class="form-control" required>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo htmlspecialchars($category['category_id']); ?>"
                                                        <?php if ($category['category_id'] == $product['category_id']) echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="product_description" id="product_description" cols="98" rows="10"
                                                class="summernote" placeholder="Description" required>
                                                      <?php echo htmlspecialchars($product['product_description']); ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hiển thị và cập nhật hình ảnh -->
                                <!-- Hiển thị và cập nhật hình ảnh -->
                                <?php foreach (['product_image', 'product_image2', 'product_image3', 'product_image4'] as $imageField): ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="mb-3">Upload <?php echo ucfirst($imageField); ?></h5>
                                            <?php
                                            // Kiểm tra nếu sản phẩm có hình ảnh
                                            $imageSrc = !empty($product[$imageField]) ? '../assets/images/' . htmlspecialchars($product[$imageField]) : '../assets/images/logo.png'; // Sử dụng hình ảnh placeholder nếu không có
                                            ?>
                                            <img class="img-fluid mb-3" src="<?php echo $imageSrc; ?>"
                                                alt="<?php echo ucfirst($imageField); ?>" style="max-width: 80px; height: auto;">

                                            <input type="hidden" name="existing_<?php echo $imageField; ?>"
                                                value="<?php echo htmlspecialchars($product[$imageField]); ?>">
                                            <input type="file" name="<?php echo $imageField; ?>" id="<?php echo $imageField; ?>"
                                                class="form-control">
                                        </div>
                                    </div>
                                <?php endforeach; ?>


                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="number" name="product_price" id="product_price" class="form-control"
                                                placeholder="Price"
                                                value="<?php echo htmlspecialchars($product['product_price']); ?>" step="0.01"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="color">Product Color</label>
                                            <input type="text" name="product_color" id="product_color" class="form-control"
                                                placeholder="Product Color"
                                                value="<?php echo htmlspecialchars($product['product_color']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" name="update_product">Update</button>
                    <a href="list_products.php" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
    </section>
</div>
<?php include('../admin/layouts/sidebar.php'); ?>