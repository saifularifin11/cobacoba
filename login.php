<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $valid_username = "admin";
    $valid_password = "123456"; 

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/login.css">
</head>
<body class="bg-gradient">
    <div class="container-fluid d-flex min-vh-100">
        <!-- Bagian Kiri (Slider) -->
        <div class="d-none d-md-block bg-dark left-section">
            <img src="/assets/img/bps3.jpg" class="img-fluid" alt="Banner">
        </div>

        <!-- Bagian Kanan (Login Form) -->
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <div class="login-box shadow rounded">
                <div class="text-center">
                    <img src="/assets/img/BPS.png" alt="Logo BPS" class="mb-3" width="90">
                    <h3>Login Admin</h3>
                </div>
                <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required placeholder="Masukkan Username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan Password">
                    </div>
                    <button type="submit" class="btn btn-login w-100">Login</button>
                </form>
                <p class="text-center mt-3 text-muted small">Copyright by <b>Magang B3DU2K</b></p>
            </div>
        </div>
    </div>
</body>
</html>
