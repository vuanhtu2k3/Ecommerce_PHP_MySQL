<?php
include('../server/connection.php');

// Khởi tạo biến
$order_details = null;
$orders = null;

// Kiểm tra nếu `order_id` tồn tại và có chi tiết đơn hàng
if (isset($_POST['order_details']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Truy vấn chi tiết đơn hàng
    $stmt = $conn->prepare('SELECT * FROM orders
        INNER JOIN users ON orders.user_id = users.user_id
        WHERE order_id = ?');
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $orders = $stmt->get_result();

    // Kiểm tra lỗi truy vấn
    if ($orders === false || $orders->num_rows === 0) {
        echo "Không tìm thấy đơn hàng.";
        exit;
    }

    // Truy vấn các sản phẩm trong đơn hàng
    $stmt1 = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt1->bind_param('i', $order_id);
    $stmt1->execute();
    $order_details = $stmt1->get_result();

    if ($order_details === false) {
        echo "Error: " . $conn->error;
        exit;
    }
} elseif (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    // Kiểm tra xem trạng thái có hợp lệ không
    if (!in_array($order_status, ['pending', 'shipped', 'delivered', 'cancelled'])) {
        echo "Trạng thái không hợp lệ.";
        exit;
    }

    $stmt2 = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt2->bind_param('si', $order_status, $order_id);

    if ($stmt2->execute()) {
        header('location:list_orders.php?message=Order status updated successfully');
        exit; // Dừng thực thi script
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No order ID provided.";
    exit;
}
?>

<?php include('../admin/layouts/app.php') ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Details</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="list_orders.php" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <?php if ($orders && $orders->num_rows > 0) { ?>
                                <?php foreach ($orders as $order) { ?>
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                            <h1 class="h5 mb-3">Shipping Address</h1>
                                            <address>
                                                <strong><?php echo htmlspecialchars($order['user_name']); ?></strong><br>
                                                <?php echo htmlspecialchars($order['user_address']); ?><br>
                                                <?php echo htmlspecialchars($order['user_phone']); ?><br>
                                                Email: <?php echo htmlspecialchars($order['user_email']); ?>
                                            </address>
                                        </div>
                                        <div class="col-sm-4 invoice-col">
                                            <br>
                                            <b>Order ID:</b> <?php echo htmlspecialchars($order['order_id']); ?><br>
                                            <b>Total:</b> <?php echo number_format($order['order_cost'], 2); ?> VND<br>
                                            <b>Status:</b>
                                            <?php
                                            // Thay đổi màu nền theo trạng thái
                                            $statusClass = 'bg-danger'; // Mặc định là màu đỏ cho "pending"

                                            if ($order['order_status'] === 'shipped') {
                                                $statusClass = 'bg-warning'; // Màu cam cho "shipped"
                                            } elseif ($order['order_status'] === 'delivered') {
                                                $statusClass = 'bg-success'; // Màu xanh cho "delivered"
                                            }
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?> p-2 text-uppercase">
                                                <?php echo htmlspecialchars($order['order_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <p>No order details available.</p>
                            <?php } ?>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($order_details && $order_details->num_rows > 0) {
                                        $subtotal = 0;
                                        while ($row = $order_details->fetch_assoc()) {
                                            $total = $row['product_price'] * 1; // Giả định số lượng là 1
                                            $subtotal += $total;
                                            echo "<tr>
                                                    <td>" . htmlspecialchars($row['product_name']) . "</td>
                                                    <td>" . number_format($row['product_price'], 3) . " VND</td>
                                                    <td>" . htmlspecialchars($row['product_quantity']) . "</td>
                                                    <td>" . number_format($total, 3) . " VND</td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No products found.</td></tr>";
                                    }
                                    ?>
                                    <tr>
                                        <th colspan="3" class="text-right p-3">Subtotal:</th>
                                        <td><?php echo number_format($subtotal, 3); ?> VND</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>30.000 VND</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td><?php echo number_format($subtotal + 30000, 3); ?> VND</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Order Status</h2>
                            <form action="order_details.php" method="POST">
                                <input type="hidden" name="order_id"
                                    value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                <div class="mb-3">
                                    <select name="order_status" id="order_status" class="form-control">
                                        <?php
                                        $status_options = ['pending', 'shipped', 'delivered', 'cancelled'];
                                        foreach ($status_options as $status) {
                                            $selected = ($order['order_status'] === $status) ? 'selected' : '';
                                            echo "<option value=\"$status\" $selected>" . ucfirst($status) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary" name="update_status">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<?php include('../admin/layouts/sidebar.php'); ?>