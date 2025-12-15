<?php
include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<!-- Important to make website responsive -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restaurant Website</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Ethiopic:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Link our CSS file -->
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/box.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/footer.css">
</head>
<body>
<!-- Link our header-part-start-->   

<section class="food-search text-center">
<div class="container">

<div class="menu  text-right">
<ul>
<li>
<a href="home.php">Home</a>
</li>
<li>
<a href="categories.php">Categories</a>
</li>
<li>
<a href="foods.php">Menu</a>
</li>
<li>
<a href="#">Contact</a>
</li>
</ul>
</div>
</div>
</section>
<!-- fOOD sEARCH Section Ends Here -->
<!-- Categories Header -->
<section class="promo-section">


    <div class="promo-row">
        <div class="promo-item">
            <img src="images/erat-migb.jpg" class="promo-img-circle" alt="Category 1">
            <p class="promo-title">እራት</p>
        </div>

        <div class="promo-item">
            <img src="images/fast-food.jpg" class="promo-img-circle" alt="Sandwich">
            <p class="promo-title">Sandwitch</p>
        </div>

        <div class="promo-item">
            <img src="images/burger3.jpg" class="promo-img-circle" alt="Chicken">
            <p class="promo-title">Chicken</p>
        </div>

        <div class="promo-item">
            <img src="images/pizza1.jpg" class="promo-img-circle" alt="Pizza">
            <p class="promo-title">Pizza</p>
        </div>

        <div class="promo-item">
            <img src="images/burgar5.jpg" class="promo-img-circle" alt="Burger">
            <p class="promo-title">Burger</p>
        </div>

        <div class="promo-item">
            <img src="images/injara.jpg" class="promo-img-circle" alt="Injera">
            <p class="promo-title">እንጀራ ምግብ</p>
        </div>
    </div>
</section>
<section class="foods-section">
    <h2 class="section-title">Our Foods</h2>
    <div class="foods-row">
        <?php
        $conn = mysqli_connect("localhost", "root", "", "delivery-foods");
        if(!$conn){
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch random foods from database
        $sql2 = "SELECT * FROM foods WHERE active='Yes' AND featured='Yes' ORDER BY RAND() LIMIT 6";
        $res2 = mysqli_query($conn, $sql2);

        if(mysqli_num_rows($res2) > 0){
            while($items = mysqli_fetch_assoc($res2)){
                $title = $items['title'];
                $price = $items['price'];
                $detail = $items['description'];
                $image = $items['image_name']; // filename from DB
                ?>
                <div class="section-divider">
                    <div class="food-menu-box">
                        <div class="food-menu-img">
                            <?php if(!empty($image)) { ?>
                                <img src="images/<?php echo $image; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                            <?php } else { ?>
                                <img src="images/default.jpg" alt="No image" class="img-responsive img-curve">
                            <?php } ?>
                        </div>

                        <div class="food-menu-desc">
                            <h4><?php echo $title; ?></h4>
                            <p class="food-price"><?php echo $price; ?> Birr</p>
                            <p class="food-detail">
                                <?php echo $detail; ?>
                            </p>
                            <br>

<a href="order.php?food_id=<?php echo $items['id']; ?>&page=home" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No foods found.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</section>







<!-- fOOD MEnu Section Starts Here -->
<section class="food-menu">

<div class="container  js-foods-html">

</section>





</body>
</html>
<?php
include('footer.php');
?>