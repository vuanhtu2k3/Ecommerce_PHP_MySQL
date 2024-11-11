<?php
session_start();
if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        session_destroy();
        header('location:login.php');
    }
}

?>

<?php include('layouts/header.php') ?>
<!-- Account Page -->
<section class="my-5 py-5">
    <div class="row container mx-auto">
        <div class="info text-center col-md-6 col-lg-12 col-sm-12">
            <h3 class="font-weight-bold text-center text-uppercase">My Account</h3>
            <div class="account-profile">
                <div class="account-info col-lg-6 col-sm-12">
                    <ul id="account-panel" class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="my_profile.php" class="nav-link font-weight-bold" role="tab">
                                <i class="fas fa-user-alt"></i> My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="my_orders.php" class="nav-link font-weight-bold" role="tab">
                                <i class="fas fa-shopping-bag"></i> My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="account.php?logout=1" class="nav-link font-weight-bold" role="tab">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="account-update col-lg-6 col-md-6">
                    <div class="mx-auto">
                        <h1 class="text-uppercase">Account Info</h1>
                    </div>
                    <p>Account: <span><?php echo $_SESSION['user_name'] ?? ''; ?></span></p>
                    <p>Email: <span><?php echo $_SESSION['user_email'] ?? ''; ?></span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('layouts/footer.php') ?>