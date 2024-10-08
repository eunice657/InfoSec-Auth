<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];

    // Check if OTP is set and hasn't expired
    if (isset($_SESSION['otp']) && (time() - $_SESSION['otp_time']) < 120) { // Check if OTP is within 120 seconds
        if ($entered_otp == $_SESSION['otp']) {
            // Redirect to a success page or protected area
            header("Location: welcome.php");
            unset($_SESSION['otp']); // Clear OTP after successful verification
            exit();
        } else {
            // Redirect back with an 'invalid' message
            header("Location: otp.php?msg=invalid%20otp"); // Use 'msg' as key
            exit();
        }
    } else {
        // Redirect back with an 'expired' message
        header("Location: otp.php?msg=expired%20otp"); // Use 'msg' as key
        exit();
    }
}
?>
