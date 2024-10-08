<?php
session_start();
include "config.php"; // Database configuration

// Set the time zone to match the local time in Ghana (Accra)
date_default_timezone_set('Africa/Accra');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the session contains the email and other necessary session variables
    if (!isset($_SESSION['email'])) {
        die("Error: Email not found in session. Please register again.");
    }

    // Reset old OTP session variables
    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);

    // Generate a new OTP
    $newOTP = rand(100000, 999999);
    $_SESSION['otp'] = $newOTP;
    $_SESSION['otp_time'] = time();

    // Get the email from session
    $email = $_SESSION['email'];

    // Optionally, get the username from the session if needed
    $username = $_SESSION['username'];

    // Update the new OTP in the database, along with the expiration time
    $otp_sent_at = date('Y-m-d H:i:s'); // Current time when OTP is generated
    $otp_expiration_time = date('Y-m-d H:i:s', strtotime('+2 minutes')); // OTP valid for 2 minutes

    $updateSql = "UPDATE users SET emailOtp = '$newOTP', otp_sent_at = '$otp_sent_at', otp_expiration_time = '$otp_expiration_time' WHERE email = '$email'";

    if ($conn->query($updateSql) === TRUE) {
        // Send the new OTP via email
        if (sendOTP($email, $newOTP)) {
            // Redirect back to verify_otp.php with a success message
            $message = "OTP resent successfully.";
            header("Location: otp.php?msg=" . urlencode($message) . "&otp_type=resend");
            exit();
        } else {
            // Handle OTP sending error
            $message = "Error sending OTP. Please try again.";
            header("Location: otp.php?msg=" . urlencode($message));
            exit();
        }
    } else {
        // Handle database update error
        echo "Error updating OTP: " . $conn->error;
    }
}

// Function to send OTP via email
function sendOTP($email, $OTP) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'euniceakomeah15@gmail.com'; // Use your email address
        $mail->Password = 'cond aogb hstk cfnj'; // Make sure to keep sensitive information secure
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('euniceakomeah15@gmail.com', 'Two Factor Authentication Website');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your New OTP Code';
        $mail->Body = "Your new One-Time Password (OTP) is <b>$OTP</b>. Please use this to complete your registration.";
        $mail->AltBody = "Your new One-Time Password (OTP) is $OTP. Please use this to complete your registration.";

        $mail->send();
        return true; // Indicate that the email was sent successfully
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false; // Indicate failure to send email
    }
}
?>
