<?php
session_start();
include "config.php"; // Database configuration

// Set the time zone to match the local time in Ghana (Accra)
date_default_timezone_set('Africa/Accra');

// Include PHPMailer classes for sending OTP via email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Make sure you have installed PHPMailer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Capture the plain password for validation

    // Validate the password strength
    if (!validatePassword($password)) {
        header("Location: register.php?msg=" . urlencode("Error: Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a digit, and a special character."));
        exit();
    }

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $otp = rand(100000, 999999); // Generate a random 6-digit OTP
    $otp_sent_at = date('Y-m-d H:i:s'); // Current time when OTP is generated
    $otp_expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes')); // OTP expires after 2 minutes

    // Check if the email already exists
    $emailCheckSql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($emailCheckSql);

    if ($result->num_rows > 0) {
        // If email is already registered, send an error message
        header("Location: register.php?msg=" . urlencode("Error: Email already registered."));
        exit();
    } else {
        // Insert user details into the database, including the OTP and its expiration time
        $sql = "INSERT INTO users (username, email, userpass, emailOtp, otp_sent_at, otp_expiration_time) 
                VALUES ('$username', '$email', '$hashedPassword', '$otp', '$otp_sent_at', '$otp_expiration_time')";

        // Store the OTP and user details in session for later verification
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        if ($conn->query($sql) === TRUE) {
            // Registration successful, send OTP to the user's email
            sendOTP($email, $otp, 'registration'); // Specify OTP type as 'registration'

            // Redirect to OTP verification page
            header("Location: otp.php?email=" . urlencode($email) . "&otp_type=registration");
            exit();
        } else {
            header("Location: register.php?msg=" . urlencode("Error: " . $conn->error));
            exit();
        }
    }
}

// Function to validate the password strength
function validatePassword($password) {
    // Regular expression to enforce password strength
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#.])[A-Za-z\d@$!%*?&#.]{8,}$/";
    return preg_match($pattern, $password);
}

// Function to send OTP via email
function sendOTP($email, $OTP, $otp_type) {
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
        $mail->Body = "Your One-Time Password (OTP) for $otp_type is <b>$OTP</b>. Please use this to complete the process.";
        $mail->AltBody = "Your One-Time Password (OTP) for $otp_type is $OTP. Please use this to complete the process.";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
