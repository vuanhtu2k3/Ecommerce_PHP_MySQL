<?php
session_start();
include('server/connection.php');

// Handle logout
if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        session_destroy();
        header('location:login.php');
        exit;
    }
}

// Fetch order details if order_id is set
$order_details = null;
if (isset($_POST['order_details']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    $stmt = $conn->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $order_details = $stmt->get_result();

    // Check for query execution errors
    if ($order_details === false) {
        echo "Error: " . $conn->error;
        exit;
    }
} else {
    echo "No order ID provided.";
    exit;
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
                            <th>Product</th>
                            <th>Product Price</th>
                            <th>Order Date</th>
                        </tr>

                        <?php if ($order_details && $order_details->num_rows > 0) {
                            while ($row = $order_details->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['order_id']; ?></td>
                                    <td>
                                        <div class="product-info">

                                            <img class="img-fluid" width="50px" height="50px"
                                                src="/assets/images/<?php echo $row['product_image']; ?>">
                                            <?php echo $row['product_name']; ?>
                                        </div>
                                    </td>
                                    <td><?php echo $row['product_price']; ?></td>
                                    <td><?php echo $row['order_date']; ?></td>
                                </tr>
                        <?php }
                        } else {
                            echo "<tr><td colspan='4'>No orders found.</td></tr>";
                        } ?>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include('layouts/footer.php') ?>