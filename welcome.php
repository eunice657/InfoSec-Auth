<?php
session_start();
include "config.php"; // Include database connection configuration

// Check if the session email exists
if (!isset($_SESSION['email'])) {
    die("Error: No user session found. Please log in again.");
}

// Retrieve the session email
$email = $_SESSION['email'];

// Fetch the username from the database using the email stored in the session
$sql = "SELECT username FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username']; // Fetch the username from the database result
} else {
    die("Error: User not found in the database.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .welcome-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #333;
            margin: 0 0 20px;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        .logout-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #4facfe;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #00c2fe;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Akwaaba, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Welcome to your dashboard. You have successfully registered and logged in.</p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
