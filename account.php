<?php
include('server/connection.php');
session_start();

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        session_destroy();
        header('location:login.php');
    }
}

if (isset($_POST['update_password'])) {
    $user_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        header('location:account.php?error=Invalid email format');
        exit();
    }

    if ($new_password !== $confirm_password) {
        header('location:account.php?error=Passwords do not match');
        exit();
    } elseif (strlen($new_password) < 6) {
        header('location:account.php?error=Password too short');
        exit();
    } else {
        // Check the old password
        $stmt = $conn->prepare('SELECT user_password FROM users WHERE user_email = ?');
        $stmt->bind_param('s', $user_email);
        $stmt->execute();
        $stmt->bind_result($hashed_old_password);
        $stmt->fetch();
        $stmt->close();

        if (md5($old_password) !== $hashed_old_password) {
            header('location:account.php?error=Old password is incorrect');
            exit();
        }

        // Hash the new password using md5
        $hashed_new_password = md5($new_password);

        $stmt = $conn->prepare('UPDATE users SET user_password = ? WHERE user_email = ?');
        if (!$stmt) {
            header('location:account.php?error=Failed to prepare statement');
            exit();
        }

        $stmt->bind_param('ss', $hashed_new_password, $user_email);
        if ($stmt->execute()) {
            header('location:account.php?message=Password updated successfully');
        } else {
            header('location:account.php?error=Failed to update password');
        }
        $stmt->close();
    }
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
                            <a href="my_profile.php" class="nav-link font-weight-bold" role="tab"
                                aria-controls="tab-login" aria-expanded="false">
                                <i class="fas fa-user-alt"></i> My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="my_orders.php" class="nav-link font-weight-bold" role="tab"
                                aria-controls="tab-login" aria-expanded="false">
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
                    <form id="account-update" action="account.php" method="POST">
                        <?php if (isset($_GET['message'])): ?>
                        <div class="alert alert-success" role="alert">
                            <p><?php echo $_GET['message']; ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <p><?php echo $_GET['error']; ?></p>
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <input type="text" placeholder="Email" id="email" name="email" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Old password" id="old_password" name="old_password"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="New password" id="new_password" name="new_password"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Confirm Password" id="confirm_password"
                                name="confirm_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Update" name="update_password" class="btn" id="login-btn">
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>


</section>


<?php include('layouts/footer.php') ?>