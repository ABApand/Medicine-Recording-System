<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        header("Location: dashboard.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Medicine Recording System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container, .dashboard {
    width: 300px;
    margin: 100px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

input[type="text"], input[type="password"] {
    width: 90%;
    padding: 10px;
    margin: 10px 0;
}

button {
    padding: 10px 20px;
    background: #28a745;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

.error {
    color: red;
}
    </style>
</head>
<body>
<div class="login-container">
    <h2>Medicine Recording System</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
