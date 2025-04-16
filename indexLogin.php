<?php
session_start();

// Jika sudah login, langsung ke index
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
}

$error = '';

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ganti sesuai data login kamu
    $valid_username = "user";
    $valid_password = "123456";

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
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
    <title>Login ke Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="text-center">Login</h4>
                        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button class="btn btn-primary w-100">Login</button>
                        </form>
                        <p class="mt-3 text-center text-muted">© Magang B3DU2K</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
