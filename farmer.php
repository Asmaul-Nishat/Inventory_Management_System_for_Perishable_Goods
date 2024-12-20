<?php
// Include database connection
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'ims';
$port = 3306;

$conn = new mysqli($hostname, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'addOrUpdate') {
        $farmId = $_POST['farmId'];
        $farmName = $_POST['farmName'];
        $farmAddress = $_POST['farmAddress'];
        $farmEmail = $_POST['farmEmail'];

        if (!empty($farmId)) {
            $stmt = $conn->prepare("SELECT * FROM farm WHERE farmId = ?");
            $stmt->bind_param("i", $farmId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE farm SET farm_name = ?, farm_address = ?, farm_email = ? WHERE farmId = ?");
                $stmt->bind_param("sssi", $farmName, $farmAddress, $farmEmail, $farmId);
                $stmt->execute();
                $message = "Farm updated successfully!";
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO farm (farmId, farm_name, farm_address, farm_email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $farmId, $farmName, $farmAddress, $farmEmail);
                $stmt->execute();
                $message = "Farm added successfully!";
            }
        } else {
            $message = "Invalid farm ID!";
        }

        echo "<script>alert('$message');</script>";
    } elseif ($action === 'delete') {
        $farmId = $_POST['farmId'];

        if (!empty($farmId)) {
            $stmt = $conn->prepare("DELETE FROM farm WHERE farmId = ?");
            $stmt->bind_param("i", $farmId);
            $stmt->execute();
            $message = "Farm deleted successfully!";
        } else {
            $message = "Invalid farm ID!";
        }

        echo "<script>alert('$message');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Dashboard</title>
    <link rel="stylesheet" href="farmer.css">
    <style>
        /* Include your CSS styles here */
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Farm Menu</h2>
            <button onclick="showModal()">Add Farm</button>
            <button onclick="location.href='harvest_dashboard.php'">Harvest</button>
            <button onclick="location.href='mainPage.php'"">Logout</button>
        </div>

        <div class="main-content">
            <header>
                <h1>Farm Dashboard</h1>
            </header>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Farmers</h3>
                    <p id="total-farmers">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) AS total FROM farm");
                        $row = $result->fetch_assoc();
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
            <div class="table-container">
                <h2>Farm Data</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Organization ID</th>
                            <th>Organization Name</th>
                            <th>Farm Address</th>
                            <th>Farm Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT farmId, farm_name, farm_address, farm_email FROM farm";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['farmId']}</td>
                                    <td>{$row['farm_name']}</td>
                                    <td>{$row['farm_address']}</td>
                                    <td>{$row['farm_email']}</td>
                                    <td>
                                        <button onclick=\"editFarm({$row['farmId']}, '{$row['farm_name']}', '{$row['farm_address']}', '{$row['farm_email']}')\">Edit</button>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='farmId' value='{$row['farmId']}'>
                                            <input type='hidden' name='action' value='delete'>
                                            <button type='submit'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="modal" class="modal" style="display:none;">
        <h3>Add/Edit Farm</h3>
        <form method="POST">
            <input type="hidden" name="action" value="addOrUpdate">
            <label>Farm ID:</label>
            <input type="number" name="farmId" id="farmId" required>
            <label>Farm Name:</label>
            <input type="text" name="farmName" id="farmName" required>
            <label>Farm Address:</label>
            <input type="text" name="farmAddress" id="farmAddress">
            <label>Farm Email:</label>
            <input type="email" name="farmEmail" id="farmEmail">
            <button type="submit">Save</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>

    <script>
        function showModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function editFarm(id, name, address, email) {
            document.getElementById('farmId').value = id;
            document.getElementById('farmName').value = name;
            document.getElementById('farmAddress').value = address;
            document.getElementById('farmEmail').value = email;
            showModal();
        }
    </script>
</body>

</html>
