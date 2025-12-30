<?php
require_once 'connection.php';
session_start();

$msg = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $user = mysqli_real_escape_string($connect, $_POST['username']);
    $pass = $_POST['password'];

    // Query your Users table
    $res = mysqli_query($connect, "SELECT user_id, username, role, password_hash FROM Users WHERE username='$user' LIMIT 1");

    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        if (password_verify($pass, $row['password_hash'])) {
            $_SESSION['user_id']  = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role'];

            // Role-based redirection
            switch ($row['role']) {
                case 'delivery':
                    header('Location: delivery_boy/assigned_order.php');
                    break;
                case 'waiter':
                    header('Location: waiter/dashboard.php');
                    break;
                case 'admin':
                    header('Location: admin/dashboard.php');
                    break;
                    case 'customer':
                    header('Location: customer/home.php');
                    break;
                default:
                    header('Location: Dashboard.php');
            }
            exit;
        }
    }
    $msg = 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Ella Hotel</title>
    <style>
        /* --- INTERNAL CSS STYLE --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2c3e50; /* Matches the header color of your dashboard */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            width: 350px;
            text-align: center;
        }

        .brand-name {
            font-size: 1.5rem;
            color: #27ae60;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        input[type="text"], 
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            outline-color: #27ae60;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #27ae60;
            border: none;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 15px;
        }

        button:hover {
            background-color: #219150;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 12px;
            color: #95a5a6;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <span class="brand-name">ELLA KITCHEN</span>
        <form method="POST">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="footer-text">Secure Access Control System</div>
    </div>

    <script>
        <?php if ($msg): ?> 
            alert('<?= addslashes($msg) ?>'); 
        <?php endif; ?>
    </script>
</body>
</html>