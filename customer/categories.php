
<?php include('header.php'); ?>
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
    <div class="header-space"></div>

<section class="categories">
    <div class="container">

        <div class="clearfix"></div>

        <div id="category-container">

        <?php
        // Database connection
        $conn = mysqli_connect("localhost", "root", "", "delivery-foods");

        // Select active + featured categories
        $sql = "SELECT * FROM categories WHERE active='Yes' AND featured='Yes'";
        $res = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($res)) {

            $id = $row['id'];
            $title = $row['title'];
            $image = $row['image_name'];
        ?>
            <a href="category-foods.php?category=<?php echo $id; ?>">
                <div class="box-3 float-container">
                    <img src="<?php echo $image; ?>" class="img-responsive img-curve">
                    <h3 class="float-text text-white"><?php echo $title; ?></h3>
                </div>
            </a>

        <?php } ?>

        </div>

        <div class="clearfix"></div>
    </div>
</section>



    
</body>
</html>
<?php include('footer.php'); ?>















