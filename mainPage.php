<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $servername = "localhost"; // Your database server
    $username = "root";        // Your database username
    $password = "";            // Your database password
    $dbname = "ims";           // Your database name

    // Get form data
    $admin_id = $_POST['admin_id'];
    $admin_password = $_POST['admin_password'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query to check the login credentials
    $sql = "SELECT * FROM login WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the login is valid
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if ($admin_password == $row['admin_password']) {
            // Set session and redirect to admin dashboard
            $_SESSION['admin_id'] = $admin_id;
            header("Location: admin_dashboard.php");
            exit;  // Make sure no further code is executed after redirection
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "User not found.";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-image: url('front-pic.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 150px; /* Adjust the size of the logo as needed */
            height: auto;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8); /* semi-transparent white */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* subtle shadow */
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .company-name{
            color:white;
        }
    </style>
</head>
<body>

    <div class="logo-container">
        <img src="logo4.png" alt="Company Logo">
        <h1 class="company-name">HarvestPro: From Field to Future</h1>
    </div>

    <div class="login-container">
        <h2>Login to Admin</h2>
        <form method="POST" action="">
            <input class="input-field" type="text" name="admin_id" placeholder="Enter your Admin ID" required>
            <input class="input-field" type="password" name="admin_password" placeholder="Enter your password" required>
            <button type="submit" class="submit-button">Login</button>
        </form>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>

</body>
</html>
