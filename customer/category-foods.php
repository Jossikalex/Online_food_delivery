<?php 
include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/footer.css">
</head>
<body>
  <?php
// Connect to database
$conn = mysqli_connect("localhost", "root", "", "delivery-foods");
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// Check if category parameter exists
if (!isset($_GET['category'])) {
    // Redirect to categories page if no category is selected
    header("Location: categories.php");
    exit();
}

$category_id = $_GET['category'];
?>

<div class="header-space"></div>

<section class="food-menu">
    <div class="container">

        <div id="food-container">
            <?php
            // Fetch foods by category
            $sql = "SELECT * FROM foods WHERE category_id='$category_id' AND active='Yes'";
            $res = mysqli_query($conn, $sql);

            if(mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                    $title = $row['title']; // food title
                    $price = $row['price'];
                    $desc = $row['description'];
                    $image = $row['image_name']; // make sure this column exists in your DB
                    ?>
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
                            <p class="food-price">ETB-<?php echo $price; ?></p>
                            <p class="food-detail"><?php echo $desc; ?></p>
                            <a href="order.php?food_id=<?php echo $row['id']; ?>" class="btn btn-primary">Order Now</a>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "<p class='text-center'>No foods found in this category.</p>";
            }
            ?>
        </div>

    </div>
</section>

<?php
mysqli_close($conn);
?>

<?php include('footer.php'); ?>
    
</body>
</html>





