<?php
// include 'config.php';
// $errors = [];

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = trim($_POST["email"]);
//     $name = $_POST['name'];
//     $password = $_POST["password"];
//     $confirm_password = $_POST["confirm_password"];

//     $otp = mt_rand(1000000,999999);

//     // Validate email
//     if (empty($email)) {
//         $errors[] = "Email is required.";
//     } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $errors[] = "Invalid email format.";
//     }

//     // Validate password one lowercase one uppercase one special charaters
//     if (empty($password)) {
//         $errors[] = "Password is required.";
//     } elseif (strlen($password) < 8) {
//         $errors[] = "Password must be at least 8 characters long.";
//     } elseif ($password !== $confirm_password) {
//         $errors[] = "Passwords do not match.";
//     }

//     // If no validation errors, proceed to register the user
//     if (empty($errors)) {
//         // Hash the password
//         $hashed_password = password_hash($password, PASSWORD_DEFAULT);


//         // Insert user into database
//         $stmt = $conn->prepare("INSERT INTO users (email, userpass, emailOtp) VALUES (?, ?, ?)");
        
//         // Check if prepare() was successful
//         if ($stmt === false) {
//             die('Prepare failed: ' . mysqli_error($conn));
//         }
        
//         $stmt->bind_param("ssi", $email, $hashed_password, $otp);

//         if ($stmt->execute()) {
//             //send OTP email
//             $_SESSION['emailid']=$email;	

//             //Code for Sending Email
//             $subject="OTP Verification";
//             $headers .= "MIME-Version: 1.0"."\r\n";
//             $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
//             $headers .= 'From:User Signup<johnmanful2002@hotmail.com>'."\r\n";                          
//             $ms.="<html></body><div><div>Dear $name,</div></br></br>";
//             $ms.="<div style='padding-top:8px;'>Thank you for registering with us. OTP for for Account Verification is $otp</div><div></div></body></html>";
//             mail($email,$subject,$ms,$headers); 
//             echo "<script>window.location.href='verify_otp.php'</script>";
           
//         } else {
//             echo "Error: " . $stmt->error;
//         }

//         $stmt->close();
//         $conn->close();
//     }
// }
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="verify_otp.php">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <br><br>
        <button type="submit">Register</button>
    </form>

    <!-- Display errors -->
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
