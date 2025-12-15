<?php 
include('header.php');

// Get URL parameters
$food_id = isset($_GET['food_id']) ? $_GET['food_id'] : 0;
$refPage = isset($_GET['page']) ? $_GET['page'] : 'home';

// Set background based on referring page
$bgImage = $refPage === 'menu' ? 'images/menu-bg.jpg' : 'images/home-bg.jpg';

// Default food info in case DB fails
$food = [
    'title' => 'Food Title',
    'price' => '450',
    'image_name' => 'images/menu-pizza.jpg'
];

// Connect to DB and fetch food info
if($food_id){
    $conn = mysqli_connect("localhost","root","","delivery-foods");
    if(!$conn) { die("Connection failed: " . mysqli_connect_error()); }

    $stmt = $conn->prepare("SELECT title, price, image_name FROM foods WHERE id=?");
    $stmt->bind_param("i", $food_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $food['title'] = isset($row['title']) ? $row['title'] : $food['title'];
        $food['price'] = isset($row['price']) ? $row['price'] : $food['price'];
        $food['image_name'] = isset($row['image_name']) ? $row['image_name'] : $food['image_name'];
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirm Order</title>
<link rel="stylesheet" href="css/order.css">
</head>
<body>

<!-- ORDER MODAL -->
<div class="order-modal" id="orderModal" style="background-image: url('<?php echo $bgImage; ?>');">

    <div class="order-box">
        <!-- CLOSE BUTTON -->
        <span class="order-close" id="closeModal">&times;</span>

        <h2>Confirm Your Order</h2>

        <!-- SELECTED FOOD -->
        <div class="selected-food">
            <img src="<?php echo $food['image_name']; ?>" alt="<?php echo $food['title']; ?>">

            <div class="food-info">
                <h3><?php echo $food['title']; ?></h3>
                <p class="food-price"><?php echo $food['price']; ?> Birr</p>

                <label>Quantity</label>
                <input type="number" value="1" min="1">
            </div>
        </div>

        <!-- DELIVERY FORM -->
        <form class="order-form" action="#" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="tel" name="phone" placeholder="09xxxxxxxx" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="address" placeholder="Delivery Address" required></textarea>

            <button type="submit">Confirm Order</button>
        </form>

    </div>

</div>

<script>
// Close modal functionality
const modal = document.getElementById('orderModal');
const closeBtn = document.getElementById('closeModal');

closeBtn.onclick = () => modal.style.display = 'none';
window.onclick = (e) => { if(e.target === modal) modal.style.display = 'none'; }
</script>

<?php include('footer.php'); ?>
</body>
</html>





