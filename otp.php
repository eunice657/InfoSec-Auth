<!-- otp_verification.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .resend {
            background-color: #28a745;
            margin-top: 10px;
        }

        .resend:hover {
            background-color: #218838;
        }
        
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter OTP</h2>
        <form action="verify_otp.php" method="POST">
            <label for="otp">OTP:</label>
            <input type="text" id="otp" name="otp" required>
            <input type="hidden" name="otp_type" value="<?php echo $_GET['otp_type']; ?>">
            <button type="submit">Verify</button>
        </form>
        <form action="resend_otp.php" method="POST" class="center">
            <button type="submit" class="resend">Resend OTP</button>
        </form>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('msg');
    if (message) {
        // Prevent body scroll
        document.body.style.overflow = "hidden";

        Swal.fire({
            title: "Notice",
            text: message,
            icon: "info",
            position: "center",
            backdrop: true,
            willClose: () => {
                // Re-enable scrolling when the alert is closed
                document.body.style.overflow = "auto";
            }
        });
    }
});
</script>

</body>
</html>
