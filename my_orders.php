<?php

session_start();
include('server/connection.php');

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        session_destroy();
        header('location:login.php');
    }
}



if (isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];


    $stmt = $conn->prepare('SELECT * FROM orders JOIN order_items ON orders.order_id = order_items.order_id WHERE orders.user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();

    // Check if there are any results
    if (!$orders) {
        echo "No orders found.";
        exit();
    }
} else {
    echo "You are not logged in.";
    exit();
}

?>

<?php include('layouts/header.php') ?>

<!--Account page-->
<section class="my-5 py-5">
    <div class="row container mx-auto">
        <div class="info text-center col-md-6 col-lg-12 col-sm-12">
            <h3 class="font-weight-bold text-center text-uppercase">My Account</h3>
            <div class="account-profile">
                <div class="account-info col-lg-6 col-sm-12">
                    <ul id="account-panel" class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="account.php" class="nav-link font-weight-bold" role="tab" aria-controls="tab-login"
                                aria-expanded="false">
                                <i class="fas fa-user-alt"></i> My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="account.php" class="nav-link font-weight-bold" role="tab" aria-controls="tab-login"
                                aria-expanded="false">
                                <i class="fas fa-shopping-bag"></i> My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="account.php?logout=1" class="nav-link font-weight-bold" role="tab"
                                aria-controls="tab-login" aria-expanded="false">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="account-update col-lg-6 col-md-6">
                    <h3 class="text-uppercase">Your Orders</h3>
                    <table class="orders mt-5">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Cost</th>
                            <th>Quantity</th>
                            <th>Order Status</th>
                            <th>Order Date</th>
                            <th>Order Details</th>
                        </tr>

                        <!-- Display orders -->
                        <?php while ($row = $orders->fetch_assoc()) { ?>
                            <tr class="text-uppercase font-weight">
                                <td><?php echo $row['order_id']; ?></td>
                                <td><?php echo $row['order_cost']; ?> VND</td>
                                <td><?php echo $row['product_quantity'] ?></td>
                                <td> <?php
                                        $status = $row['order_status'];
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

                                <td><?php echo $row['order_date']; ?></td>
                                <td>
                                    <form action="order_details.php" method="POST">
                                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                        <input type="submit" name="order_details" class="btn custom-badge" value="Details">
                                    </form>



                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('layouts/footer.php') ?>