<?php

session_start();

?>

<?php include('layouts/header.php'); ?>

<!--Image background-->
<section id="home">
    <div class="container">
        <h5>NEW ARRIVAL</h5>
        <h1><span>Best Price</span> This SeaSon</h1>
        <p>Eshop offer the best product for the most affordable of price</p>
        <button>Shop Now</button>
    </div>
</section>

<!-- Brand -->
<section id="brand" class="container-fluid">
    <div class="row ">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="/assets/images/brand.png">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="/assets/images/brand1.jpg">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="/assets/images/brand2.jpg">
        <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="/assets/images/brand5.png">
    </div>
</section>

<!--Shop-->
<section id="shop" class="w-100">
    <div class="row p-0 m-0">
        <div class="one col-lg-4 col-md-6 col-sm-12 p-0">
            <img class="img-fluid" src="/assets/images/new-product.jpg">
            <div class="details">
                <h2>Product Fashion</h2>
                <button class="text-uppercase rounded-2"> Shop Now</button>
            </div>
        </div>
        <div class="one col-lg-4 col-md-6 col-sm-12 p-0">
            <img class="img-fluid" src="/assets/images/new-product1.jpg">
            <div class="details">
                <h2>Product Fashion</h2>
                <button class="text-uppercase rounded-2"> Shop Now</button>
            </div>
        </div>
        <div class="one col-lg-4 col-md-6 col-sm-12 p-0">
            <img class="img-fluid" src="/assets/images/new-product2.jpg">
            <div class="details">
                <h2>Product Fashion</h2>
                <button class="text-uppercase rounded-2"> Shop Now</button>
            </div>
        </div>
    </div>
</section>

<!--Featured-->
<section id="featured" class="my-5 py-5">

    <div class="container text-center mt-5 py-5">
        <h3 class="text-uppercase fs-1">Our Featured</h3>
        <hr>
        <p class="fs-4">Here you can check out for our product</p>
    </div>


    <div class="row mx-auto container-fluid">

        <?php include('server/get_featured_product.php'); ?>

        <?php while ($row = $featured_product->fetch_assoc()) {  ?>
        <div class="product text-center  col-lg-3 col-md-6 col-sm-12">
            <img class="img-fluid mb-3" src="/assets/images/<?php echo $row['product_image']; ?>">
            <div class="star">
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
            </div>
            <h3 class="p-product"><?php echo $row['product_name']; ?></h3>
            <p class="p-price"><?php echo $row['product_price']; ?> VND</p>
            <a href="single_product.php?product_id=<?php echo $row['product_id'] ?>"> <button
                    class="buy-btn p-2 text-uppercase  rounded-2 text-white "> Buy Now</button></a>
        </div>
        <?php } ?>

    </div>


</section>
<!--Mid Banner-->
<section id="banner" class="my-5">
    <div class="container">
        <h4>MID SEASON's SALE</h4>
        <h1>AUTUMN COLLECTION <br> UP TO 45% OFF</h1>
        <button class="  p-2 rounded-2 "> SHOP NOW</button>
    </div>
</section>
<!--Clothes-->
<section id="featured" class="my-5 py-5">

    <div class="container text-center mt-5 py-5">
        <h3 class="text-uppercase fs-1">Dress && Coast</h3>
        <hr>
        <p class="fs-4">Here you can check out for our product</p>
    </div>


    <div class="row mx-auto container-fluid">

        <?php include('server/get_clothes.php') ?>

        <?php while ($row = $clothes->fetch_assoc()) { ?>
        <div onclick="window.location.href = 'single_product.php?product_id=<?php echo $row['product_id'] ?>'"
            class="product text-center  col-lg-3 col-md-6 col-sm-12">
            <img class="img-fluid mb-3" src="/assets/images/<?php echo $row['product_image']; ?>">

            <div class="star">
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
                <i class="fas fa-star "></i>
            </div>
            <h3 class="p-product"><?php echo $row['product_name']; ?></h3>
            <p class="p-price"><?php echo $row['product_price']; ?> VND</p>
            <a href="single_product.php?product_id=<?php echo $row['product_id'] ?>"> <button
                    class="buy-btn p-2 text-uppercase  rounded-2 text-white m-3 "> Buy Now</button></a>
        </div>
        <?php } ?>

    </div>


</section>

<?php include('layouts/footer.php'); ?>