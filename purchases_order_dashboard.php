<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ims";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all purchase orders
$query = "SELECT * FROM purchase_order";
$result = $conn->query($query);

$purchaseOrders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $purchaseOrders[] = $row;
    }
}

// Handle saving a purchase order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'] ?? null;
    $orderDate = $_POST['orderDate'];
    $deliveryLocation = $_POST['deliveryLocation'];
    $distributorId = $_POST['distributorId'];

    if ($orderId) {
        // Update order
        $updateQuery = "UPDATE purchase_order SET order_date = ?, delivery_location = ?, distributor_id = ? WHERE orderId = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssii", $orderDate, $deliveryLocation, $distributorId, $orderId);
        $stmt->execute();
    } else {
        // Add new order
        $insertQuery = "INSERT INTO purchase_order (order_date, delivery_location, distributor_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssi", $orderDate, $deliveryLocation, $distributorId);
        $stmt->execute();
    }

    header("Location: purchases_order_dashboard.php");
    exit();
}

// Handle deleting a purchase order
if (isset($_GET['delete'])) {
    $orderId = $_GET['delete'];
    $deleteQuery = "DELETE FROM purchase_order WHERE orderId = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    header("Location: purchases_order_dashboard.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: rgb(11, 147, 11);
            color: white;
            padding: 20px;
            height: 100vh;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            cursor: pointer;
            background-color: #fff;
            color: rgb(11, 147, 11);
            font-weight: bold;
        }

        .sidebar button:hover {
            background-color: rgb(180, 255, 180);
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        header {
            margin-bottom: 20px;
        }

        .search-bar {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: rgb(240, 240, 240);
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .modal.active {
            display: block;
        }

        .modal input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal button {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .modal button[type="submit"] {
            background-color: rgb(11, 147, 11);
            color: white;
        }

        .modal button[type="button"] {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Purchase Order Menu</h2>
            <button onclick="showModal()">Add Purchase Order</button>
            <button onclick="location.href='shipment_dashboard.php'">Shipment</button>
            <button onclick="location.href='distributor_dashboard.php'">Distributor</button>
            <button onclick="location.href='mainPage.php'">Logout</button>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Purchase Order Dashboard</h1>
            </header>

            <input type="text" id="search" placeholder="Search orders..." class="search-bar" onkeyup="filterTable()" />

            <table id="purchaseOrderTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Distributor ID</th>
                        <th>Delivery Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchaseOrders as $order) : ?>
                        <tr>
                            <td><?= $order['orderId'] ?></td>
                            <td><?= $order['order_date'] ?></td>
                            <td><?= $order['distributor_id'] ?></td>
                            <td><?= $order['delivery_location'] ?></td>
                            <td>
                                <button onclick='editOrder(<?= json_encode($order) ?>)'>Edit</button>
                                <a href="?delete=<?= $order['orderId'] ?>" onclick="return confirm('Delete this order?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="modal">
        <form method="POST" id="orderForm">
            <input type="hidden" name="orderId" id="orderId" />
            <label>Order Date:</label>
            <input type="date" name="orderDate" id="orderDate" required />
            <label>Distributor ID:</label>
            <input type="number" name="distributorId" id="distributorId" required />
            <label>Delivery Location:</label>
            <input type="text" name="deliveryLocation" id="deliveryLocation" required />
            <button type="submit">Save</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>

    <script>
        function showModal() {
            document.getElementById('modal').classList.add('active');
            document.getElementById('orderForm').reset();
        }

        function closeModal() {
            document.getElementById('modal').classList.remove('active');
        }

        function editOrder(order) {
            document.getElementById('orderId').value = order.orderId;
            document.getElementById('orderDate').value = order.order_date;
            document.getElementById('distributorId').value = order.distributor_id;
            document.getElementById('deliveryLocation').value = order.delivery_location;
            showModal();
        }

        function filterTable() {
            const search = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('#purchaseOrderTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        }
    </script>
</body>

</html>
