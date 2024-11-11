<?php
include('../server/connection.php');

if (isset($_POST['create_product'])) {
    // Kiểm tra và xác thực dữ liệu đầu vào
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_description = $_POST['product_description'];
    //$product_price = $_POST['product_price'];
    $product_price = filter_var($_POST['product_price'], FILTER_VALIDATE_FLOAT);
    $product_color = $_POST['product_color'];

    if ($product_price == null && $product_price < 0) {
        echo "Price is not valued";
    }


    // Xử lý upload hình ảnh
    $image_files = ['image', 'image2', 'image3', 'image4']; //Tao mang luu tru cac truong de nhap vao
    $image_names = []; // Luu tru ten cua cac hinh anh duoc tai len

    foreach ($image_files as $key => $image) {
        if (isset($_FILES[$image]) && $_FILES[$image]['error'] === UPLOAD_ERR_OK) // Kiem tra co phai co truong file hoat dong k
        {
            $extension = pathinfo($_FILES[$image]['name'], PATHINFO_EXTENSION);
            $image_name = $product_name . ($key + 1) . '.' . $extension;
            $image_path = "../assets/images/" . $image_name;

            if (move_uploaded_file($_FILES[$image]['tmp_name'], $image_path)) {
                $image_names[] = $image_name;
            } else {
                die("Lỗi tải lên tệp $image.");
            }
        } else {
            // Nếu không có hình ảnh, thêm giá trị NULL
            $image_names[] = null;
        }
    }

    // Chuẩn bị câu lệnh SQL
    $stmt = $conn->prepare('INSERT INTO products (product_name, category_id, product_description, product_image, product_image2, product_image3, product_image4, product_price, product_color)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $stmt->bind_param(
        "sssssssds",
        $product_name,
        $product_category,
        $product_description,
        $image_names[0],
        $image_names[1],
        $image_names[2],
        $image_names[3],
        $product_price,
        $product_color
    );

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        header('Location: list_products.php?message=Product added successfully');
        exit; // Thêm exit sau khi redirect để tránh chạy thêm mã phía dưới
    } else {
        header('Location: list_products.php?error=Error product failed');
        exit; // Thêm exit sau khi redirect
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>

<?php include('../admin/layouts/app.php') ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="list_products.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="create_products.php" method="POST" enctype="multipart/form-data">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name">Product Name</label>
                                            <input type="text" name="product_name" id="product_name"
                                                class="form-control" placeholder="Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="category">Product Category</label>
                                            <select name="product_category" id="product_category" class="form-control"
                                                required>
                                                <option value="">Select Category</option>
                                                <?php
                                                // Fetch categories from the database
                                                $result = $conn->query('SELECT category_id, category_name FROM category');
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row['category_id']) . '">' . htmlspecialchars($row['category_name']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="product_description" id="product_description" cols="98"
                                                rows="10" class="summernote" placeholder="Description"
                                                required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php foreach (['image', 'image2', 'image3', 'image4'] as $imageField): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Upload <?php echo ucfirst($imageField); ?></h2>
                                    <input type="file" name="<?php echo $imageField; ?>" id="<?php echo $imageField; ?>"
                                        class="form-control" required>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="price">Price</label>
                                    <input type="number" name="product_price" id="product_price" class="form-control"
                                        placeholder="Price" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="color">Product Color</label>
                                    <input type="text" name="product_color" id="product_color" class="form-control"
                                        placeholder="Product Color" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" name="create_product">Create</button>
                    <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<?php include('../admin/layouts/sidebar.php') ?>