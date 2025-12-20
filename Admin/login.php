<?php
require_once 'connection.php';
session_start();

$msg = '';                                     // plain text only (will be passed to JS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = mysqli_real_escape_string($connect, $_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    $res = mysqli_query($connect,
          "SELECT staff_id, Fname, Lname, role, password_hash
           FROM Staff
           WHERE username='$user'
           LIMIT 1");
    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        if (password_verify($pass, $row['password_hash'])) {
            $_SESSION['admin_id']       = $row['staff_id'];
            $_SESSION['adminUsername']  = $row['Fname'].' '.$row['Lname'];
            $_SESSION['adminRole']      = $row['role'];
            header('Location: Dashboard.php');   // success redirect
            exit;
        }
    }
    $msg = 'Invalid username or password';     // will be consumed by JS
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ella Kitchen Cafe Admin</title>

    <!-- same assets -->
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <!--  identical html block  -->
    <div class="content-container">
        <div class="welcome-container">
            <h1 class="welcome-heading">Welcome to Ella Kitchen Cafe<br>Administration Portal</h1>
            <p class="welcome-subtitle">
                Access your restaurant management dashboard to monitor operations, update menus,
                track orders, and manage customer experiences.
            </p>
            <div class="welcome-divider"></div>
        </div>

        <button class="glass-login-btn" id="openLoginBtn">
            <i class="fas fa-sign-in-alt"></i>
            Access Admin Dashboard
        </button>
    </div>

    <!-- Login Modal -->
    <div class="login-modal" id="loginModal">
        <div class="login-box">
            <button class="close-modal" id="closeLoginBtn" aria-label="Close login modal"><i class="fas fa-times"></i></button>
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Login to Ella Kitchen Cafe Admin Panel</p>
            </div>

            <form class="login-form" id="loginForm" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="login-btn" id="submitLoginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to Dashboard
                </button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /* ---- modal open/close ---- */
        const modal = document.getElementById('loginModal');
        document.getElementById('openLoginBtn').onclick  = () => { modal.classList.add('active'); document.body.style.overflow='hidden'; };
        document.getElementById('closeLoginBtn').onclick = () => { modal.classList.remove('active'); document.body.style.overflow='auto'; };
        modal.onclick = e => { if(e.target===modal) { modal.classList.remove('active'); document.body.style.overflow='auto'; } };

        /* ---- handle form submit ---- */
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const btn = document.getElementById('submitLoginBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating…';
        });

        /* ---- show error if any (PHP injected) ---- */
        <?php if ($msg): ?>
            Swal.fire({ icon: 'error', title: 'Login failed', text: '<?= addslashes($msg) ?>' });
        <?php endif; ?>
    </script>
</body>
</html>