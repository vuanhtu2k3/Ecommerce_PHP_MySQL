<?php

// Kết nối đến cơ sở dữ liệu
include('../server/connection.php');

// Truy vấn kết hợp bảng orders với bảng users để lấy thông tin người dùng
$stmt = $conn->prepare('
    SELECT orders.*, users.user_name, users.user_email 
    FROM orders 
    INNER JOIN users ON orders.user_id = users.user_id
');
$stmt->execute();
$orders = $stmt->get_result();

?>

<?php include('../admin/layouts/app.php') ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6 text-right">
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
                        <div class="input-group" style="width: 250px;">
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
                    <?php if(isset($_GET['message'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['message'] ?>
                    </div>
                    <?php endif; ?>
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Order Cost</th>
                                <th>Order Status</th>
                                <th>Order Date</th>
                                <th>Order Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) { ?>
                            <tr>
                                <td><?php echo $order['order_id'] ?></td>
                                <td><?php echo $order['user_name'] ?></td>
                                <td><?php echo $order['user_email'] ?></td>
                                <td><?php echo $order['order_cost'] ?></td>
                                <td>

                                    <?php 
        $status = $order['order_status']; 
        // Xác định màu nền dựa trên trạng thái đơn hàng
        $statusClass = 'bg-danger'; // Mặc định là màu đỏ cho "pending"

        if ($status === 'shipped') {
            $statusClass = 'bg-warning'; // Màu cam cho "shipped"
        } elseif ($status === 'delivered') {
            $statusClass = 'bg-success'; // Màu xanh cho "delivered"
        }
    ?>
                                    <span class="badge <?php echo $statusClass; ?> p-2 text-uppercase">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                    <br>
                                </td>
                                <td><?php echo $order['order_date'] ?></td>
                                <td>
                                    <form action="../admin/order_details.php" method="POST">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <input type="submit" name="order_details"
                                            style="background-color: coral; color: aliceblue; border-radius: 8px; padding: 8px 16px; border: none; cursor: pointer;"
                                            value="Details">
                                    </form>


                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <ul class="pagination pagination m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include('../admin/layouts/sidebar.php') ?>