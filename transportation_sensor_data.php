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
        $sensorId = $_POST['sensorId'];
        $vehicleRegistrationNumber = $_POST['vehicleRegistrationNumber'];
        $timestamp = $_POST['timestamp'];
        $actualTemperature = $_POST['actualTemperature'];
        $actualHumidity = $_POST['actualHumidity'];
        $actualOxidationLevel = $_POST['actualOxidationLevel'];
        $co2Level = $_POST['co2Level'];
        $movementSensorData = $_POST['movementSensorData'];

        if (!empty($sensorId)) {
            $stmt = $conn->prepare("SELECT * FROM transportation_sensor_data WHERE sensorId = ?");
            $stmt->bind_param("s", $sensorId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE transportation_sensor_data SET vehicleRegistrationNumber = ?, timestamp = ?, actual_temperature = ?, actual_humidity = ?, actual_oxidation_level = ?, co2_level = ?, movement_sensor_data = ? WHERE sensorId = ?");
                $stmt->bind_param("ssdddsss", $vehicleRegistrationNumber, $timestamp, $actualTemperature, $actualHumidity, $actualOxidationLevel, $co2Level, $movementSensorData, $sensorId);
                $stmt->execute();
                $message = "Sensor data updated successfully!";
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO transportation_sensor_data (sensorId, vehicleRegistrationNumber, timestamp, actual_temperature, actual_humidity, actual_oxidation_level, co2_level, movement_sensor_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdddsss", $sensorId, $vehicleRegistrationNumber, $timestamp, $actualTemperature, $actualHumidity, $actualOxidationLevel, $co2Level, $movementSensorData);
                $stmt->execute();
                $message = "Sensor data added successfully!";
            }
        } else {
            $message = "Invalid sensor ID!";
        }

        echo "<script>alert('$message');</script>";
    } elseif ($action === 'delete') {
        $sensorId = $_POST['sensorId'];

        if (!empty($sensorId)) {
            $stmt = $conn->prepare("DELETE FROM transportation_sensor_data WHERE sensorId = ?");
            $stmt->bind_param("s", $sensorId);
            $stmt->execute();
            $message = "Sensor data deleted successfully!";
        } else {
            $message = "Invalid sensor ID!";
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
    <title>Transportation Sensor Data Dashboard</title>
    <link rel="stylesheet" href="transportation_sensor_data.css">
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Sensor Data Menu</h2>
            <button onclick="showModal()">Add Sensor Data</button>
            <button onclick="location.href='mainPage.php'">Logout</button>
        </div>

        <div class="main-content">
            <header>
                <h1>Sensor Data Dashboard</h1>
            </header>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Sensor Records</h3>
                    <p id="total-sensor-data">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) AS total FROM transportation_sensor_data");
                        $row = $result->fetch_assoc();
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
            <div class="table-container">
                <h2>Sensor Data</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sensor ID</th>
                            <th>Vehicle Registration Number</th>
                            <th>Timestamp</th>
                            <th>Temperature (°C)</th>
                            <th>Humidity (%)</th>
                            <th>Oxidation Level</th>
                            <th>CO₂ Level</th>
                            <th>Movement Sensor Data</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT sensorId, vehicleRegistrationNumber, timestamp, actual_temperature, actual_humidity, actual_oxidation_level, co2_level, movement_sensor_data FROM transportation_sensor_data";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['sensorId']}</td>
                                    <td>{$row['vehicleRegistrationNumber']}</td>
                                    <td>{$row['timestamp']}</td>
                                    <td>{$row['actual_temperature']}</td>
                                    <td>{$row['actual_humidity']}</td>
                                    <td>{$row['actual_oxidation_level']}</td>
                                    <td>{$row['co2_level']}</td>
                                    <td>{$row['movement_sensor_data']}</td>
                                    <td>
                                        <button onclick=\"editSensorData('{$row['sensorId']}', '{$row['vehicleRegistrationNumber']}', '{$row['timestamp']}', {$row['actual_temperature']}, {$row['actual_humidity']}, {$row['actual_oxidation_level']}, {$row['co2_level']}, '{$row['movement_sensor_data']}')\">Edit</button>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='sensorId' value='{$row['sensorId']}'>
                                            <input type='hidden' name='action' value='delete'>
                                            <button type='submit'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="modal" class="modal" style="display:none;">
        <h3>Add/Edit Sensor Data</h3>
        <form method="POST">
            <input type="hidden" name="action" value="addOrUpdate">
            <label>Sensor ID:</label>
            <input type="text" name="sensorId" id="sensorId" required>
            <label>Vehicle Registration Number:</label>
            <input type="text" name="vehicleRegistrationNumber" id="vehicleRegistrationNumber" required>
            <label>Timestamp:</label>
            <input type="datetime-local" name="timestamp" id="timestamp" required>
            <label>Temperature (°C):</label>
            <input type="text" name="actualTemperature" id="actualTemperature" required>
            <label>Humidity (%):</label>
            <input type="text" name="actualHumidity" id="actualHumidity" required>
            <label>Oxidation Level:</label>
            <input type="text" name="actualOxidationLevel" id="actualOxidationLevel" required>
            <label>CO₂ Level:</label>
            <input type="text" name="co2Level" id="co2Level" required>
            <label>Movement Sensor Data:</label>
            <input type="text" name="movementSensorData" id="movementSensorData">
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
            document.getElementById('sensorId').value = '';
            document.getElementById('vehicleRegistrationNumber').value = '';
            document.getElementById('timestamp').value = '';
            document.getElementById('actualTemperature').value = '';
            document.getElementById('actualHumidity').value = '';
            document.getElementById('actualOxidationLevel').value = '';
            document.getElementById('co2Level').value = '';
            document.getElementById('movementSensorData').value = '';
        }

        function editSensorData(sensorId, vehicleRegistrationNumber, timestamp, actualTemperature, actualHumidity, actualOxidationLevel, co2Level, movementSensorData) {
            document.getElementById('sensorId').value = sensorId;
            document.getElementById('vehicleRegistrationNumber').value = vehicleRegistrationNumber;
            document.getElementById('timestamp').value = timestamp;
            document.getElementById('actualTemperature').value = actualTemperature;
            document.getElementById('actualHumidity').value = actualHumidity;
            document.getElementById('actualOxidationLevel').value = actualOxidationLevel;
            document.getElementById('co2Level').value = co2Level;
            document.getElementById('movementSensorData').value = movementSensorData;
            showModal();
        }
    </script>
    <!-- <script src="transportation_sensor_data.js"></script> -->
</body>

</html>
``
