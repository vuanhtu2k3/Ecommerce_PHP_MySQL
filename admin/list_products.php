<?php

include('../server/connection.php');

// Set the number of products per page to 3
$limit = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page or set default to 1
$offset = ($page - 1) * $limit;

// Fetch total number of products
$total_results = $conn->query('SELECT COUNT(*) as count FROM products')->fetch_assoc()['count'];
$total_pages = ceil($total_results / $limit);

// Fetch products for the current page
$stmt = $conn->prepare('SELECT products.*,category.category_name
 FROM products
 INNER JOIN category ON products.category_id = category.category_id
  LIMIT ?, ?');
$stmt->bind_param('ii', $offset, $limit);
$stmt->execute();
$products = $stmt->get_result();

?>

<?php include('../admin/layouts/app.php'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Products</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="create_products.php" class="btn btn-primary">New Product</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right"
                                placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['message']; ?>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                    <?php endif; ?>
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="80">Product Image</th>
                                <th>Product Name</th>
                                <th>Product Category</th>
                                <th>Price</th>
                                <th width="100">Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $products->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['product_id']; ?></td>
                                <td><img class="img-fluid mb-3"
                                        src="/assets/images/<?php echo $row['product_image']; ?>"></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['category_name']; ?></td>
                                <td><?php echo $row['product_price']; ?> VND</td>
                                <td>
                                    <svg class="text-success-500 h-6 w-6 text-success"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </td>
                                <td>
                                    <a href="edit_products.php?product_id=<?php echo $row['product_id'] ?>">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="delete_product.php?product_id=<?php echo $row['product_id'] ?>"
                                        class="text-danger w-4 h-4 mr-1">
                                        <svg wire:loading.remove.delay="" wire:target=""
                                            class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination m-0 float-right">
                        <!-- Show previous button if not on the first page -->
                        <?php if ($page > 1) { ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">«</a></li>
                        <?php } ?>

                        <!-- Loop through pages and create a link for each -->
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php } ?>

                        <!-- Show next button if not on the last page -->
                        <?php if ($page < $total_pages) { ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">»</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include('../admin/layouts/sidebar.php'); ?>