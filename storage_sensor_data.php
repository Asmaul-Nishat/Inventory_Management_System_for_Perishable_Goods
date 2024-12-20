<?php
// Database connection
$host = '127.0.0.1';
$db = 'ims';
$user = 'root'; // Update this with your database username
$password = ''; // Update this with your database password

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all data from the storage_sensor_data table
$sql_select = "SELECT sensorId, timeStamp, actual_temperature, actual_humidity, 
               actual_oxidation_level, co2_level, storageId
               FROM storage_sensor_data";
$result = $conn->query($sql_select);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storage Sensor Dashboard</title>
    <link rel="stylesheet" href="transportation.css">
</head>

<body>
    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Main Content -->
        <div class="main-content">

            <!-- Stats -->
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Sensor Data</h3>
                    <p id="total-vehicles"><?php echo isset($result) ? $result->num_rows : 0; ?></p>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <h2>Sensor Data</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sensor Data ID</th>
                            <th>Timestamp</th>
                            <th>Temperature</th>
                            <th>Humidity</th>
                            <th>Oxidation Level</th>
                            <th>CO2 Level</th>
                            <th>Storage ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['sensorId']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['timeStamp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['actual_temperature']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['actual_humidity']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['actual_oxidation_level']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['co2_level']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['storageId']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No data found in the database.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>