<?php
session_start();
include "config.php";
// Include the PHPMailer classes (Assuming you have installed PHPMailer via Composer or manually)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../InfoSec_LABBE/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $otp = rand(100000, 999999); // Generate a random 6-digit OTP

    // Check if email already exists
    $emailCheckSql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($emailCheckSql);

    if ($result->num_rows > 0) {
        // If email already exists, show an error message
        echo "Error: Email already registered.";
    } else {
        // Store the user details along with the OTP in the database
        $sql = "INSERT INTO users (username, email, userpass, emailOtp) VALUES ('$username', '$email', '$password', '$otp')";

        // Store OTP in the session
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time();
        $_SESSION['username'] = $username;


        if ($conn->query($sql) === TRUE) {
            // If registration is successful, send the OTP email
            sendOTP($email, $otp);

            // Redirect to OTP verification page
            header("Location: otp.php?email=" . urlencode($email));
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Function to send OTP via email
function sendOTP($email, $OTP) {
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
        $mail->Body = "Your One-Time Password (OTP) is <b>$OTP</b>. Please use this to complete your registration.";
        $mail->AltBody = "Your One-Time Password (OTP) is $OTP. Please use this to complete your registration.";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
