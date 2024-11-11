<?php

include("server/connection.php");

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $stmt = $conn->prepare('SELECT * FROM products WHERE product_id = ?');

    $stmt->bind_param('i', $product_id);

    $stmt->execute();

    $sg_product = $stmt->get_result();

    $stmt1 = $conn->prepare("SELECT * FROM products  LIMIT 4 OFFSET 8");
    $stmt1->execute();
    $related_products = $stmt1->get_result();
} else {
    header("location: index.php");
}

?>




<?php include('layouts/header.php') ?>

<!--Single Product-->

<section class=" container single_product my-5 pt-5">
    <div class="row mt-5">

        <?php while ($row = $sg_product->fetch_assoc()) { ?>



            <div class="col-lg-5 col-md-6">
                <img src="/assets/images/<?php echo $row['product_image'] ?>" class="img-fluid w-100 pb-2 main-img"
                    id="mainImg">

                <div class="small-img-group">
                    <div class="small-img-col">
                        <img src="/assets/images/<?php echo $row['product_image2'] ?>" class="small-img">
                    </div>
                    <div class="small-img-col">
                        <img src="/assets/images/<?php echo $row['product_image3'] ?>" class="small-img">
                    </div>
                    <div class="small-img-col">
                        <img src="/assets/images/<?php echo $row['product_image4'] ?>" class="small-img">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <h2 class="py-4"><?php echo $row['product_name']; ?></h2>
                <h4><?php echo $row['product_price']; ?> VND</h4>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>">
                    <label>Select Size:</label>
                    <div class="size-options">
                        <input type="radio" name="product_size" id="size_L" value="L" hidden>
                        <label for="size_L" class="size-label">L</label>

                        <input type="radio" name="product_size" id="size_XL" value="XL" hidden>
                        <label for="size_XL" class="size-label">XL</label>

                        <input type="radio" name="product_size" id="size_XXL" value="XXL" hidden>
                        <label for="size_XXL" class="size-label">XXL</label>
                    </div>

                    <input type="number" name="product_quantity" value="1" min="1" class="mt-3">
                    <button class="buy-btn rounded-2 text-uppercase" type="submit" name="add_to_cart">Add To Cart</button>
                </form>
                <h3 class="py-5 text-uppercase">Product Details</h3>
                <p><?php echo $row['product_description']; ?></p>
            </div>

            <style>
                .size-options {
                    display: flex;
                    gap: 10px;
                    margin-top: 5px;
                }

                .size-label {
                    padding: 8px 12px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    cursor: pointer;
                    background-color: #f8f8f8;
                    transition: background-color 0.3s;
                }

                .size-options input[type="radio"]:checked+.size-label {
                    background-color: #007bff;
                    color: white;
                    border-color: #007bff;
                }
            </style>

    </div>
<?php } ?>
</section>
<section id="featured" class="my-5 py-5">

    <div class="container text-center mt-5 py-5">
        <h3 class="text-uppercase fs-1">Related Product</h3>
    </div>


    <div class="row mx-auto container-fluid">
        <?php foreach ($related_products as $related_product) { ?>
            <div class="product text-center  col-lg-3 col-md-6 col-sm-12">
                <img class="img-fluid mb-3" src="/assets/images/<?php echo $related_product['product_image'] ?>">
                <div class="star">
                    <i class="fas fa-star "></i>
                    <i class="fas fa-star "></i>
                    <i class="fas fa-star "></i>
                    <i class="fas fa-star "></i>
                    <i class="fas fa-star "></i>
                </div>
                <h3 class="p-product"><?php echo $related_product['product_name'] ?></h3>
                <p class="p-price"><?php echo $related_product['product_price'] ?> VND</p>
                <a href="single_product.php?product_id=<?php echo $related_product['product_id'] ?>"> <button
                        class="buy-btn p-2 text-uppercase  rounded-2 text-white m-3 "> Buy Now</button></a>
            </div>
        <?php } ?>

    </div>


</section>




<?php include('layouts/footer.php') ?>


<script>
    var mainImg = document.getElementById('mainImg');
    var small_Img = document.getElementsByClassName('small-img');

    for (let i = 0; i <= 4; i++) {
        small_Img[i].addEventListener('click', function() {
            mainImg.src = small_Img[i].src;
        });
    }
</script>