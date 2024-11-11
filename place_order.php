<?php

session_start();

include('server/connection.php');

if (!isset($_SESSION['logged_in'])) {
    header('location: ../checkout.php?message=Please login/register to place an order');
} else {



    if (isset($_POST['place_order'])) {

        // 1. get info user and store in database
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $user_id = $_SESSION['user_id'];
        $order_status = "Pending";
        $order_cost = $_SESSION['total'];
        $order_date = date('Y-m-d H:i:s');


        $stmt = $conn->prepare("INSERT INTO orders(order_cost, order_status, user_id, user_phone, user_city,user_address, order_date) VALUES(?,?,?,?,?,?,?); ");


        $stmt->bind_param('isissss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

        $stmt_status = $stmt->execute();

        if (!$stmt_status) {
            header('location:index.php');
        }

        // 2. issue new order and  get info order and store in database
        $order_id = $stmt->insert_id;




        // 3. get product from cart

        foreach ($_SESSION['cart'] as $key => $value) {

            $product = $_SESSION['cart'][$key];

            $product_id = $product['product_id'];
            $product_name = $product['product_name'];
            $product_price = $product['product_price'];
            $product_quantity = $product['product_quantity'];
            $product_image = $product['product_image'];

            // 4. store each single item in order_items database
            $stmt1 = $conn->prepare("INSERT INTO order_items( order_id,product_id, product_name,product_quantity, product_image,user_id,order_date,product_price)
         VALUES(?,?,?,?,?,?,?,?)");

            $stmt1->bind_param('iisisiss', $order_id, $product_id, $product_name, $product_quantity, $product_image, $user_id, $order_date, $product_price);
            $stmt1->execute();
        }




        // 5. remove everthing from cart
        unset($_SESSION['cart']);

        // 6. infro user successfull or there is a problem
        header("location:../payment.php?order_status=Order successfully");
    }
}
