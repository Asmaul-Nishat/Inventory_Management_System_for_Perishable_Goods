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
        $shipmentId = $_POST['shipmentId'];
        $shipmentDate = $_POST['shipmentDate'];
        $expectedDeliveryDate = $_POST['expectedDeliveryDate'];
        $shipmentProductQuantity = $_POST['shipmentProductQuantity'];
        $productLoadingDate = $_POST['productLoadingDate'];
        $productLoadingTime = $_POST['productLoadingTime'];
        $orderId = $_POST['orderId'];
        $vehicleRegistrationNumber = $_POST['vehicleRegistrationNumber'];
        $storeId = $_POST['storeId'];
        $storageId = $_POST['storageId'];

        if (!empty($shipmentId)) {
            $stmt = $conn->prepare("SELECT * FROM shipment WHERE shipmentId = ?");
            $stmt->bind_param("s", $shipmentId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE shipment SET shipment_date = ?, expected_delivery_date = ?, shipment_product_quantity = ?, product_loading_date = ?, product_loading_time = ?, orderId = ?, vehicleRegistrationNumber = ?, storeId = ?, storageId = ? WHERE shipmentId = ?");
                $stmt->bind_param("ssisisssss", $shipmentDate, $expectedDeliveryDate, $shipmentProductQuantity, $productLoadingDate, $productLoadingTime, $orderId, $vehicleRegistrationNumber, $storeId, $storageId, $shipmentId);
                $stmt->execute();
                $message = "Shipment updated successfully!";
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO shipment (shipmentId, shipment_date, expected_delivery_date, shipment_product_quantity, product_loading_date, product_loading_time, orderId, vehicleRegistrationNumber, storeId, storageId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssisisssss", $shipmentId, $shipmentDate, $expectedDeliveryDate, $shipmentProductQuantity, $productLoadingDate, $productLoadingTime, $orderId, $vehicleRegistrationNumber, $storeId, $storageId);
                $stmt->execute();
                $message = "Shipment added successfully!";
            }
        } else {
            $message = "Invalid shipment ID!";
        }

        echo "<script>alert('$message');</script>";
    } elseif ($action === 'delete') {
        $shipmentId = $_POST['shipmentId'];

        if (!empty($shipmentId)) {
            $stmt = $conn->prepare("DELETE FROM shipment WHERE shipmentId = ?");
            $stmt->bind_param("s", $shipmentId);
            $stmt->execute();
            $message = "Shipment deleted successfully!";
        } else {
            $message = "Invalid shipment ID!";
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
    <title>Shipment Dashboard</title>
    <link rel="stylesheet" href="shipment_dashboard.css">
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Shipment Menu</h2>
            <button onclick="showModal()">Add Shipment</button>
            <button onclick="logout()">Logout</button>
        </div>

        <div class="main-content">
            <header>
                <h1>Shipment Dashboard</h1>
            </header>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Shipments</h3>
                    <p id="total-shipments">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) AS total FROM shipment");
                        $row = $result->fetch_assoc();
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
            <div class="table-container">
                <h2>Shipment Data</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Shipment ID</th>
                            <th>Shipment Date</th>
                            <th>Expected Delivery Date</th>
                            <th>Shipment Product Quantity</th>
                            <th>Product Loading (Date, Time)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT shipmentId, shipment_date, expected_delivery_date, shipment_product_quantity, product_loading_date, product_loading_time FROM shipment";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['shipmentId']}</td>
                                    <td>{$row['shipment_date']}</td>
                                    <td>{$row['expected_delivery_date']}</td>
                                    <td>{$row['shipment_product_quantity']}</td>
                                    <td>{$row['product_loading_date']} {$row['product_loading_time']}</td>
                                    <td>
                                        <button onclick=\"editShipment('{$row['shipmentId']}', '{$row['shipment_date']}', '{$row['expected_delivery_date']}', {$row['shipment_product_quantity']}, '{$row['product_loading_date']}', '{$row['product_loading_time']}')\">Edit</button>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='shipmentId' value='{$row['shipmentId']}'>
                                            <input type='hidden' name='action' value='delete'>
                                            <button type='submit'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div id="modal" class="modal" style="display:none;">
        <h3>Add/Edit Shipment</h3>
        <form method="POST">
            <input type="hidden" name="action" value="addOrUpdate">
            <label>Shipment ID:</label>
            <input type="text" name="shipmentId" id="shipmentId" required>
            <label>Shipment Date:</label>
            <input type="date" name="shipmentDate" id="shipmentDate" required>
            <label>Expected Delivery Date:</label>
            <input type="date" name="expectedDeliveryDate" id="expectedDeliveryDate" required>
            <label>Shipment Product Quantity:</label>
            <input type="number" name="shipmentProductQuantity" id="shipmentProductQuantity" required>
            <label>Product Loading Date:</label>
            <input type="date" name="productLoadingDate" id="productLoadingDate" required>
            <label>Product Loading Time:</label>
            <input type="time" name="productLoadingTime" id="productLoadingTime" required>
            <label>Order ID:</label>
            <input type="text" name="orderId" id="orderId">
            <label>Vehicle Registration Number:</label>
            <input type="text" name="vehicleRegistrationNumber" id="vehicleRegistrationNumber">
            <label>Store ID:</label>
            <input type="text" name="storeId" id="storeId">
            <label>Storage ID:</label>
            <input type="text" name="storageId" id="storageId">
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
            document.getElementById('shipmentId').value = '';
            document.getElementById('shipmentDate').value = '';
            document.getElementById('expectedDeliveryDate').value = '';
            document.getElementById('shipmentProductQuantity').value = '';
            document.getElementById('productLoadingDate').value = '';
            document.getElementById('productLoadingTime').value = '';
            document.getElementById('orderId').value = '';
            document.getElementById('vehicleRegistrationNumber').value = '';
            document.getElementById('storeId').value = '';
            document.getElementById('storageId').value = '';
        }

        function editShipment(shipmentId, shipmentDate, expectedDeliveryDate, shipmentProductQuantity, productLoadingDate, productLoadingTime) {
            document.getElementById('shipmentId').value = shipmentId;
            document.getElementById('shipmentDate').value = shipmentDate;
            document.getElementById('expectedDeliveryDate').value = expectedDeliveryDate;
            document.getElementById('shipmentProductQuantity').value = shipmentProductQuantity;
            document.getElementById('productLoadingDate').value = productLoadingDate;
            document.getElementById('productLoadingTime').value = productLoadingTime;
            showModal();
        }
    </script>
</body>

</html>
