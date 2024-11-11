<?php

session_start();
calculateTotalCart();



if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    //If user has already added to a product
    if (isset($_SESSION['cart'])) {

        $product_array_ids = array_column($_SESSION['cart'], "product_id");
        // If product already been added to cart or not.
        if (!in_array($_POST['product_id'], $product_array_ids)) {
            $product_array = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_image' => $_POST['product_image'],
                'product_price' => $_POST['product_price'],
                'product_quantity' => $_POST['product_quantity']
            );
            $_SESSION['cart'][$_POST['product_id']] = $product_array;
        } else {
            echo '<script> alert("Product already added to cart");  </script>';
        }
    } else {
        // If the first product
        $product_array = array(
            'product_id' => $_POST['product_id'],
            'product_name' => $_POST['product_name'],
            'product_image' => $_POST['product_image'],
            'product_price' => $_POST['product_price'],
            'product_quantity' => $_POST['product_quantity']
        );
        $_SESSION['cart'][$_POST['product_id']] = $product_array;
    }
    calculateTotalCart();
} else if (isset($_POST['remove_product'])) {
    //Remove product from cart
    unset($_SESSION['cart'][$_POST['product_id']]);
    calculateTotalCart();
} else if (isset($_POST['update_quantity'])) {
    // // update quantity


    // //get id and product quantity from form.
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];
    //update product quantity in session cart.
    $product_array = $_SESSION['cart'][$product_id];
    // update quantity
    $product_array['product_quantity'] = $product_quantity;
    // return array
    $_SESSION['cart'][$product_id] = $product_array;
} else {
    echo "Your cart is empty";
}



function calculateTotalCart()
{
    $total = 0;
    $shipping = 30.000;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            $price = $value['product_price'];
            $quantity = $value['product_quantity'];
            $total += ($price * $quantity) + $shipping;
        }
        $_SESSION['total'] = $total;
    } else {
        $_SESSION['total'] = 0; // Set total to 0 if the cart is empty
    }
}





?>






<?php include('layouts/header.php') ?>

<!--Checkout page-->

<section class="my-5 py-5">
    <h2 class=" text-uppercase text-center"> Check out</h2>
    <hr class="mx-auto">
    <div class="container">
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_GET['message'] ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <form id="check-out" method="POST" action="place_order.php">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h3>Shipping Address</h3>
                    </div>

                    <div class="card-body  shadow-lg border-0 checkout-form">

                        <div class="row form-group">

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="address" id="address" class="form-control"
                                        placeholder="Address"></textarea>
                                </div>
                            </div>


                        </div>

                    </div>

                </div>
                <div class="col-md-4 m-4">
                    <div class="sub-title">
                        <h3>Order Summery</h3>
                    </div>

                    <div class="cart-summery  shadow-lg border-0">
                        <?php foreach ($_SESSION['cart'] as $key => $value) { ?>
                            <div class="d-flex justify-content-between pb-2">
                                <h6><?php echo $value['product_name'] ?> x <?php echo $value['product_quantity'] ?></h6>
                                <h6><?php echo $value['product_price'] ?> VND</h6>
                            </div>
                        <?php } ?>
                        <div class="d-flex justify-content-between pb-2">
                            <h6>Shipping</h6>
                            <h6>30.000 VND</h6>
                        </div>
                        <div class="d-flex justify-content-between summery-end">
                            <h6>Total</h6>
                            <h6><strong><?php echo number_format($_SESSION['total'], 3, '.', '.') . ' VND'; ?>
                                </strong>
                            </h6>
                        </div>
                        <button class="btn btn-success" name="place_order"> Checkout</button>
                    </div>
                </div>
            </form>
        </div>

    </div>


</section>


<?php include('layouts/footer.php') ?>