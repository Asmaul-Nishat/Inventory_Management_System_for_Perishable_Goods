<?php
// Database connection
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'ims';
$port = 3306;

$conn = new mysqli($hostname, $username, $password, $database, $port);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'addOrUpdate') {
        $vehicleRegNumber = $_POST['vehicleRegNumber'];
        $manufacturingYear = $_POST['manufacturingYear'];
        $mileage = $_POST['mileage'];
        $lastServicingDate = $_POST['lastServicingDate'];

        if (!empty($vehicleRegNumber)) {
            $stmt = $conn->prepare("SELECT * FROM transportation WHERE vehicleRegistrationNumber = ?");
            $stmt->bind_param("s", $vehicleRegNumber);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE transportation SET manufacturing_year = ?, mileage = ?, last_servicing_date = ? WHERE vehicleRegistrationNumber = ?");
                $stmt->bind_param("iiss", $manufacturingYear, $mileage, $lastServicingDate, $vehicleRegNumber);
                $stmt->execute();
                $message = "Vehicle updated successfully!";
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO transportation (vehicleRegistrationNumber, manufacturing_year, mileage, last_servicing_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("siis", $vehicleRegNumber, $manufacturingYear, $mileage, $lastServicingDate);
                $stmt->execute();
                $message = "Vehicle added successfully!";
            }
        } else {
            $message = "Invalid vehicle registration number!";
        }

        echo "<script>alert('$message');</script>";
    } elseif ($action === 'delete') {
        $vehicleRegNumber = $_POST['vehicleRegNumber'];

        if (!empty($vehicleRegNumber)) {
            $stmt = $conn->prepare("DELETE FROM transportation WHERE vehicleRegistrationNumber = ?");
            $stmt->bind_param("s", $vehicleRegNumber);
            $stmt->execute();
            $message = "Vehicle deleted successfully!";
        } else {
            $message = "Invalid vehicle registration number!";
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
    <title>Transportation Dashboard</title>
    <link rel="stylesheet" href="transportation.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Transportation Menu</h2>
            <button onclick="showModal()">Add Vehicle</button>
            <button onclick="window.location.href='transportation_sensor_data.php'">Transportation Sensor Data</button>
            <button onclick="logout()">Logout</button>
        </div>

        <div class="main-content">
            <header>
                <h1>Transportation Dashboard</h1>
            </header>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Vehicles</h3>
                    <p id="total-vehicles">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) AS total FROM transportation");
                        $row = $result->fetch_assoc();
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
            <div class="table-container">
                <h2>Vehicle Data</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Vehicle Registration Number</th>
                            <th>Manufacturing Year</th>
                            <th>Mileage</th>
                            <th>Last Servicing Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT vehicleRegistrationNumber, manufacturing_year, mileage, last_servicing_date FROM transportation";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['vehicleRegistrationNumber']}</td>
                                    <td>{$row['manufacturing_year']}</td>
                                    <td>{$row['mileage']}</td>
                                    <td>{$row['last_servicing_date']}</td>
                                    <td>
                                        <button onclick=\"editVehicle('{$row['vehicleRegistrationNumber']}', {$row['manufacturing_year']}, {$row['mileage']}, '{$row['last_servicing_date']}')\">Edit</button>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='vehicleRegNumber' value='{$row['vehicleRegistrationNumber']}'>
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
        <h3>Add/Edit Vehicle</h3>
        <form method="POST">
            <input type="hidden" name="action" value="addOrUpdate">
            <label>Vehicle Registration Number:</label>
            <input type="text" name="vehicleRegNumber" id="vehicleRegNumber" required>
            <label>Manufacturing Year:</label>
            <input type="number" name="manufacturingYear" id="manufacturingYear" required>
            <label>Mileage (km):</label>
            <input type="number" name="mileage" id="mileage" required>
            <label>Last Servicing Date:</label>
            <input type="date" name="lastServicingDate" id="lastServicingDate" required>
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
            resetModal();
        }

        function resetModal() {
            document.getElementById('vehicleRegNumber').value = '';
            document.getElementById('manufacturingYear').value = '';
            document.getElementById('mileage').value = '';
            document.getElementById('lastServicingDate').value = '';
        }

        function editVehicle(vehicleRegNumber, manufacturingYear, mileage, lastServicingDate) {
            document.getElementById('vehicleRegNumber').value = vehicleRegNumber;
            document.getElementById('manufacturingYear').value = manufacturingYear;
            document.getElementById('mileage').value = mileage;
            document.getElementById('lastServicingDate').value = lastServicingDate;
            showModal();
        }
    </script>
</body>
</html>
