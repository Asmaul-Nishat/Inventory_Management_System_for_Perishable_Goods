<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "ims";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add a consumer
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_consumer'])) {
    $name = $_POST['consumer_name'];
    $address = $_POST['consumer_address'];
    $history = $_POST['purchase_history'];

    $sql = "INSERT INTO consumer (consumer_name, consumer_address, purchase_history) 
            VALUES ('$name', '$address', '$history')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New consumer added successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle form submission to edit a consumer
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit_consumer'])) {
    $id = $_POST['consumer_id'];
    $name = $_POST['consumer_name'];
    $address = $_POST['consumer_address'];
    $history = $_POST['purchase_history'];

    $sql = "UPDATE consumer SET consumer_name='$name', consumer_address='$address', purchase_history='$history' 
            WHERE consumerId=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Consumer updated successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle delete consumer action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql = "DELETE FROM consumer WHERE consumerId=$delete_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Consumer deleted successfully!');</script>";
    } else {
        echo "Error deleting consumer: " . $conn->error;
    }
}

// Fetch all consumers
$sql = "SELECT * FROM consumer";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Management</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: #333;
            margin: 20px;
            line-height: 1.6;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgb(11, 147, 11);
            color: white;
            padding: 10px 20px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        header button {
            background-color: white;
            color: rgb(11, 147, 11);
            border: 2px solid white;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        header button:hover {
            background-color: rgb(8, 120, 8);
            color: white;
        }

        h2, h3 {
            text-align: center;
            color: rgb(11, 147, 11);
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border: 2px solid rgb(11, 147, 11);
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: rgb(11, 147, 11);
        }

        form input,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid rgb(11, 147, 11);
            border-radius: 5px;
        }

        form button {
            background-color: rgb(11, 147, 11);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: rgb(8, 120, 8);
        }

        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border: 2px solid rgb(11, 147, 11);
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: rgb(11, 147, 11);
            color: white;
            font-size: 16px;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e8f5e9;
        }

        table td button {
            margin: 0 5px;
            background-color: rgb(11, 147, 11);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        table td button:hover {
            background-color: rgb(8, 120, 8);
        }
    </style>
</head>
<body>
    <header>
        <h1>Consumer Dashboard</h1>
        <button onclick="location.href='customer_sales_record_dashboard.php'">Consumer Sales Record</button>
        <button onclick="location.href='mainPage.php'">Logout</button>
    </header>

    <h2>Consumer Management System</h2>

    <!-- Add Consumer Form -->
    <form method="POST" action="">
        <label>Consumer Name:</label>
        <input type="text" name="consumer_name" required>
        <label>Consumer Address:</label>
        <input type="text" name="consumer_address" required>
        <label>Purchase History:</label>
        <textarea name="purchase_history"></textarea>
        <button type="submit" name="add_consumer">Add Consumer</button>
    </form>

    <h3>Consumer List</h3>
    <table>
        <thead>
            <tr>
                <th>Consumer ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Purchase History</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['consumerId']}</td>
                            <td>{$row['consumer_name']}</td>
                            <td>{$row['consumer_address']}</td>
                            <td>{$row['purchase_history']}</td>
                            <td>
                                <button onclick='openEditPopup({$row["consumerId"]}, \"{$row["consumer_name"]}\", \"{$row["consumer_address"]}\", \"{$row["purchase_history"]}\")'>Edit</button>
                                <button onclick='confirmDelete({$row["consumerId"]})'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No consumers found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this consumer?")) {
                window.location.href = "?delete_id=" + id;
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
