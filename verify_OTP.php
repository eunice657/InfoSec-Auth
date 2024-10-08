<?php
session_start();
require 'config.php'; // Assuming config.php contains the DB connection

// Set the time zone to match the local time in Ghana
date_default_timezone_set('Africa/Accra');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];

    // Output the session variables for debugging
    echo "Session Data: ";
    print_r($_SESSION);
    echo "<br>";

    // Check if the username is set in the session
    if (!isset($_SESSION['username'])) {
        die("Session username not found. Please log in again.");
    }
    $username = $_SESSION['username'];

    // Fetch the otp_type from POST request
    if (isset($_POST['otp_type'])) {
        $otp_type = $_POST['otp_type'];
    } else {
        header("Location: otp.php?msg=missing%20otp%20type");
        exit();
    }

    // Fetch the OTP, expiration time, and email from the database
    $stmt = $conn->prepare("SELECT emailOtp, otp_expiration_time, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $stored_otp = $user['emailOtp'];
        $otp_expiration_time = $user['otp_expiration_time'];
        $user_email = $user['email'];  // Fetch the user's email

        // Debugging output for expiration time and username
        echo "Stored Username: $username<br>";
        echo "Stored OTP: $stored_otp<br>";
        echo "User Email: $user_email<br>";
        echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";
        echo "OTP Expiration Time: $otp_expiration_time<br>";

        $current_time = time();
        $expiration_timestamp = strtotime($otp_expiration_time);
        
        // Debugging output for timestamps
        echo "Current Time (timestamp): " . $current_time . "<br>";
        echo "OTP Expiration Time (timestamp): " . $expiration_timestamp . "<br>";

        // Check if the OTP is valid and hasn't expired
        if ($entered_otp == $stored_otp && $current_time < $expiration_timestamp) {
            // OTP is valid and not expired
            
            // Store the user's email in the session
            $_SESSION['email'] = $user_email;

            // Redirect based on the OTP type
            if ($otp_type === 'registration' || $otp_type === 'resend') {
                header("Location: welcome.php");
            } elseif ($otp_type === 'login') {
                header("Location: welcome.php");
            }
            exit();
        } else {
            // Handle OTP expiration or invalid OTP
            if ($current_time > $expiration_timestamp) {
                header("Location: otp.php?msg=expired%20otp&otp_type=$otp_type");
            } else {
                header("Location: otp.php?msg=invalid%20otp&otp_type=$otp_type");
            }
            exit();
        }
    } else {
        // Output the actual query used for debugging
        echo "Query used: SELECT emailOtp, otp_expiration_time, email FROM users WHERE username = '$username'<br>";
        header("Location: otp.php?msg=user%20not%20found&otp_type=$otp_type");
        exit();
    }
}
?>
