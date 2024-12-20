<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Handle form submission to add or update shipment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Check if shipmentId exists to determine whether to update or insert
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM shipment WHERE shipmentId = ?");
        $checkStmt->bind_param("s", $shipmentId);
        $checkStmt->execute();
        $checkStmt->bind_result($exists);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($exists) {
            // Update the shipment
            $stmt = $conn->prepare("UPDATE shipment SET shipment_date = ?, expected_delivery_date = ?, shipment_product_quantity = ?, product_loading_date = ?, product_loading_time = ?, orderId = ?, vehicleRegistrationNumber = ?, storeId = ?, storageId = ? WHERE shipmentId = ?");
            $stmt->bind_param("ssisisssss", $shipmentDate, $expectedDeliveryDate, $shipmentProductQuantity, $productLoadingDate, $productLoadingTime, $orderId, $vehicleRegistrationNumber, $storeId, $storageId, $shipmentId);
        } else {
            // Insert new shipment
            $stmt = $conn->prepare("INSERT INTO shipment (shipmentId, shipment_date, expected_delivery_date, shipment_product_quantity, product_loading_date, product_loading_time, orderId, vehicleRegistrationNumber, storeId, storageId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisisssss", $shipmentId, $shipmentDate, $expectedDeliveryDate, $shipmentProductQuantity, $productLoadingDate, $productLoadingTime, $orderId, $vehicleRegistrationNumber, $storeId, $storageId);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Shipment saved successfully!'); window.location.href = ''; </script>";
        } else {
            echo "<script>alert('Error saving shipment: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }

    if ($action === 'delete') {
        $shipmentId = $_POST['shipmentId'];
        $stmt = $conn->prepare("DELETE FROM shipment WHERE shipmentId = ?");
        $stmt->bind_param("s", $shipmentId);
        if ($stmt->execute()) {
            echo "<script>alert('Shipment deleted successfully!'); window.location.href = ''; </script>";
        } else {
            echo "<script>alert('Error deleting shipment: " . $stmt->error . "');</script>";
        }
        $stmt->close();
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
            <button onclick="location.href='purchases_order_dashboard.php'">Purchase Order</button>
            <button onclick="location.href='transportation.php'">Transportation</button>
            <button onclick="location.href='store_dashboard.php'">Store</button>
            <button onclick="location.href='storage.php'">Storage</button>
            <button onclick="location.href='product.php'">Product</button>
            <button onclick="location.href='mainPage.php'">Logout</button>
            <input type="text" id="search" placeholder="Search..." onkeyup="searchTable()">
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
                <table id="shipment-table">
                    <thead>
                        <tr>
                            <th>Shipment ID</th>
                            <th>Shipment Date</th>
                            <th>Expected Delivery Date</th>
                            <th>Shipment Product Quantity</th>
                            <th>Product Loading (Date, Time)</th>
                            <th>Order ID</th>
                            <th>Vehicle Registration Number</th>
                            <th>Store ID</th>
                            <th>Storage ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM shipment";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $formattedTime = date('H:i', strtotime($row['product_loading_time'])); // Format time for input
                                echo "<tr>
                                    <td>{$row['shipmentId']}</td>
                                    <td>{$row['shipment_date']}</td>
                                    <td>{$row['expected_delivery_date']}</td>
                                    <td>{$row['shipment_product_quantity']}</td>
                                    <td>{$row['product_loading_date']} {$formattedTime}</td>
                                    <td>{$row['orderId']}</td>
                                    <td>{$row['vehicleRegistrationNumber']}</td>
                                    <td>{$row['storeId']}</td>
                                    <td>{$row['storageId']}</td>
                                    <td>
                                        <button onclick=\"editShipment('{$row['shipmentId']}', '{$row['shipment_date']}', '{$row['expected_delivery_date']}', '{$row['shipment_product_quantity']}', '{$row['product_loading_date']}', '{$formattedTime}', '{$row['orderId']}', '{$row['vehicleRegistrationNumber']}', '{$row['storeId']}', '{$row['storageId']}')\">Edit</button>
                                        <form method=\"POST\" style=\"display:inline;\">
                                            <input type=\"hidden\" name=\"action\" value=\"delete\">
                                            <input type=\"hidden\" name=\"shipmentId\" value=\"{$row['shipmentId']}\">
                                            <button type=\"submit\">Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal" class="modal" style="display:none;">
        <h3 id="modal-title">Add Shipment</h3>
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
            resetModal();
            document.getElementById('modal-title').textContent = 'Add Shipment';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
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

        function editShipment(shipmentId, shipmentDate, expectedDeliveryDate, shipmentProductQuantity, productLoadingDate, productLoadingTime, orderId, vehicleRegistrationNumber, storeId, storageId) {
            showModal();
            document.getElementById('modal-title').textContent = 'Edit Shipment';
            document.getElementById('shipmentId').value = shipmentId;
            document.getElementById('shipmentDate').value = shipmentDate;
            document.getElementById('expectedDeliveryDate').value = expectedDeliveryDate;
            document.getElementById('shipmentProductQuantity').value = shipmentProductQuantity;
            document.getElementById('productLoadingDate').value = productLoadingDate;
            document.getElementById('productLoadingTime').value = productLoadingTime;
            document.getElementById('orderId').value = orderId;
            document.getElementById('vehicleRegistrationNumber').value = vehicleRegistrationNumber;
            document.getElementById('storeId').value = storeId;
            document.getElementById('storageId').value = storageId;
        }

        function searchTable() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const table = document.getElementById('shipment-table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(searchInput)) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        }
    </script>
</body>

</html>
