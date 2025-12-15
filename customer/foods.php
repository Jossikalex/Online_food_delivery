<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restaurant Menu</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/box.css">
<link rel="stylesheet" href="css/footer.css">
<link rel="stylesheet" href="css/header.css">
</head>
<body>

<!-- Menu Navigation -->


<?php 
include('header.php');

// DB connection
$conn = mysqli_connect("localhost", "root", "", "delivery-foods");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Menu</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/footer.css">
<link rel="stylesheet" href="css/header.css">
</head>

<body>

<section class="food-menu">
<div class="container">


<?php
$sql = "SELECT * FROM foods WHERE active='Yes'";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0){
while($row = mysqli_fetch_assoc($res)){

$id     = $row['id'];
$title  = $row['title'];
$price  = $row['price'];
$detail = $row['description'];
$image  = $row['image_name'];
?>

<div class="food-menu-box">

<div class="food-menu-img">
<?php if($image != "") { ?>
    <img src="images/<?php echo $image; ?>" alt="">
<?php } else { ?>
    <img src="images/default.jpg" alt="No Image">
<?php } ?>
</div>

<div class="food-menu-desc">
<h4><?php echo $title; ?></h4>
<p class="food-price"><?php echo $price; ?> Birr</p>
<p class="food-detail"><?php echo $detail; ?></p>


<a href="order.php?food_id=<?php echo $id; ?>&page=menu" class="btn btn-primary">Order Now</a>
</div>

</div>

<?php } } else { ?>
<p class="text-center">No foods available.</p>
<?php } ?>

<div class="clearfix"></div>

</div>
</section>

<?php include('footer.php'); ?>
</body>
</html>


