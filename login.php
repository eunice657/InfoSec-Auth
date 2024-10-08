<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            /* Added margin-bottom for spacing below the form */
            margin-bottom: 20px; 
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
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

        /* Styles for the "Don't have an account?" div */
        .div {
            text-align: center;
            
            font-size: 14px;  
            color: #555;      
        }

        .div a {
            color: #007bff; /* Link color */
            text-decoration: none; /* Removes underline from link */
            font-weight: bold; /* Makes link text bold */
        }

        .div a:hover {
            text-decoration: underline; /* Underlines link on hover */
        }

        /* For smaller screens */
        @media (max-width: 400px) {
            form {
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <form action="login_action.php" method="POST">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Login</button>

        <div class="div">
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
    </form>
    


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
