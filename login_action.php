<?php
require 'database_conn.php'; // Your database connection file
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['userpass'])) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expires'] = time(); // 2 minutes expiry
        $_SESSION['username'] = $username;

        // Send OTP via email
        mail($user['email'], "Your OTP Code", "Your OTP is: $otp");

        header('Location: otp_verification.php'); // Redirect to OTP verification page
        exit();
    } else {
        echo "Invalid username or password!";
    }
}
?>
