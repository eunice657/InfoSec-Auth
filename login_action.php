<?php
session_start();
require 'config.php'; // Your database connection file

// Set the time zone to match the local time in Ghana (Accra)
date_default_timezone_set('Africa/Accra');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Ensure PHPMailer is loaded via Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['userpass'])) {
        // Store the user's email and username in session variables
        $_SESSION['email'] = $user['email'];  // Use $user['email'] from the fetched record
        $_SESSION['username'] = $username;    // Save username for OTP verification

        // Generate OTP for login
        $otp = rand(100000, 999999);
        $_SESSION['login_otp'] = $otp;
        $otp_sent_at = date('Y-m-d H:i:s'); // Capture the current time when OTP is sent
        $otp_expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes')); // 2 minutes for login OTP

        // Store OTP, otp_sent_at, and otp_expiration_time in the database
        $stmt = $conn->prepare("UPDATE users SET emailOtp = ?, otp_sent_at = ?, otp_expiration_time = ? WHERE username = ?");
        $stmt->bind_param("ssss", $otp, $otp_sent_at, $otp_expiration_time, $username);
        $stmt->execute();

        // Send OTP via email using PHPMailer
        if (sendOTP($user['email'], $otp)) {
            // Redirect to OTP verification page for login
            header("Location: otp.php?email=" . urlencode($user['email']) . "&otp_type=login");
            exit();
        } else {
            // If there was an issue sending the email, show an error
            header('Location: login.php?msg=' . urlencode("Error sending OTP email."));
            exit();
        }
    } else {
        // Redirect back to login page with an error message
        header('Location: login.php?msg=' . urlencode("Invalid username or password."));
        exit();
    }
}

// Function to send OTP via PHPMailer
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'euniceakomeah15@gmail.com';
        $mail->Password = 'cond aogb hstk cfnj'; // Make sure to keep sensitive information secure
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('euniceakomeah15@gmail.com', 'Two Factor Authentication Website');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your One-Time Password (OTP) is <b>$otp</b>. Please use this to complete the process.";
        $mail->AltBody = "Your One-Time Password (OTP) is $otp. Please use this to complete the process.";

        $mail->send();
        return true;  // Return true on success
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;  // Return false on failure
    }
}
?>
