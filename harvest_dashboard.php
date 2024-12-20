<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "ims";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST requests for adding, updating, or deleting harvest records
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data['action'] === 'add') {
        $stmt = $conn->prepare("INSERT INTO harvest (harvestId, farmId, quantity_harvested, date_of_harvest, expiration_date, shelf_life, quantity_unit_of_storage, productId, storageId, vehicleRegistrationNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssissssss", $data['harvestId'], $data['farmId'], $data['quantity'], $data['dateOfHarvest'], $data['expirationDate'], $data['shelfLife'], $data['storageQuantity'], $data['productId'], $data['storageId'], $data['vehicleReg']);
        $stmt->execute();
        echo "Harvest record added successfully.";
        exit;
    }

    if ($data['action'] === 'update') {
        $stmt = $conn->prepare("UPDATE harvest SET farmId=?, quantity_harvested=?, date_of_harvest=?, expiration_date=?, shelf_life=?, quantity_unit_of_storage=?, productId=?, storageId=?, vehicleRegistrationNumber=? WHERE harvestId=?");
        $stmt->bind_param("ssssisssss", $data['farmId'], $data['quantity'], $data['dateOfHarvest'], $data['expirationDate'], $data['shelfLife'], $data['storageQuantity'], $data['productId'], $data['storageId'], $data['vehicleReg'], $data['harvestId']);
        $stmt->execute();
        echo "Harvest record updated successfully.";
        exit;
    }

    if ($data['action'] === 'delete') {
        $stmt = $conn->prepare("DELETE FROM harvest WHERE harvestId=?");
        $stmt->bind_param("s", $data['harvestId']);
        $stmt->execute();
        echo "Harvest record deleted successfully.";
        exit;
    }
}

// Fetch harvest data
$sql = "SELECT * FROM harvest";
$result = $conn->query($sql);
$harvests = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $harvests[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Harvest Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid black;
        }

        .modal.active {
            display: block;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }

        .nav-button {
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        .sidebar {
            width: 200px;
            float: left;
        }

        .main-content {
            margin-left: 220px;
        }

        .table-container {
            margin-top: 30px;
        }

        .wrapper {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Harvest Menu</h2>
            <button onclick="showHarvestModal()" class="nav-button">Add Harvest</button>
            <button onclick="location.href='product.php'" class="nav-button">Product</button>
            <button onclick="location.href='storage.php'" class="nav-button">Storage</button>
            <button onclick="location.href='transportation.php'" class="nav-button">Transportation</button>
            <button onclick="location.href='mainPage.php'" class="nav-button">Logout</button>
        </div>

        <div class="main-content">
            <header>
                <h1>Harvest Dashboard</h1>
            </header>

            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Harvests</h3>
                    <p id="total-harvests"><?php echo count($harvests); ?></p>
                </div>
            </div>

            <div class="table-container">
                <h2>Harvest Data</h2>
                <input type="text" id="harvest-search" placeholder="Search harvest records..." onkeyup="filterHarvestTable()" class="search-bar">
                <table>
                    <thead>
                        <tr>
                            <th>Harvest ID</th>
                            <th>Farm ID</th>
                            <th>Quantity Harvested</th>
                            <th>Date of Harvest</th>
                            <th>Expiration Date</th>
                            <th>Shelf Life</th>
                            <th>Quantity Unit to Storage</th>
                            <th>Product ID</th>
                            <th>Storage ID</th>
                            <th>Vehicle Registration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="harvest-table-body">
                        <?php foreach ($harvests as $harvest) { ?>
                        <tr>
                            <td><?php echo $harvest['harvestId']; ?></td>
                            <td><?php echo $harvest['farmId']; ?></td>
                            <td><?php echo $harvest['quantity_harvested']; ?></td>
                            <td><?php echo $harvest['date_of_harvest']; ?></td>
                            <td><?php echo $harvest['expiration_date']; ?></td>
                            <td><?php echo $harvest['shelf_life']; ?></td>
                            <td><?php echo $harvest['quantity_unit_of_storage']; ?></td>
                            <td><?php echo $harvest['productId']; ?></td>
                            <td><?php echo $harvest['storageId']; ?></td>
                            <td><?php echo $harvest['vehicleRegistrationNumber']; ?></td>
                            <td>
                                <button onclick="editHarvest(<?php echo $harvest['harvestId']; ?>)">Edit</button>
                                <button onclick="deleteHarvest(<?php echo $harvest['harvestId']; ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal" id="harvestModal">
        <h3>Add/Edit Harvest</h3>
        <label>Harvest ID:</label>
        <input type="text" id="modal-harvest-id" />
        <label>Farm ID:</label>
        <input type="text" id="modal-farm-id" />
        <label>Quantity Harvested:</label>
        <input type="number" id="modal-quantity-harvested" />
        <label>Date of Harvest:</label>
        <input type="date" id="modal-date-harvest" />
        <label>Expiration Date:</label>
        <input type="date" id="modal-expiration-date" />
        <label>Shelf Life (in days):</label>
        <input type="number" id="modal-shelf-life" />
        <label>Quantity Unit to Storage:</label>
        <input type="number" id="modal-storage-quantity" />
        <label>Product ID:</label>
        <input type="text" id="modal-product-id" />
        <label>Storage ID:</label>
        <input type="text" id="modal-storage-id" />
        <label>Vehicle Registration Number:</label>
        <input type="text" id="modal-vehicle-registration" />
        <button onclick="saveHarvest()">Save</button>
        <button onclick="closeModal()">Cancel</button>
    </div>

    <script>
        let editIndex = null;

        function showHarvestModal() {
            clearModalFields();
            document.getElementById("harvestModal").classList.add("active");
            editIndex = null;
        }

        function closeModal() {
            document.getElementById("harvestModal").classList.remove("active");
        }

        function clearModalFields() {
            document.getElementById("modal-harvest-id").value = "";
            document.getElementById("modal-farm-id").value = "";
            document.getElementById("modal-quantity-harvested").value = "";
            document.getElementById("modal-date-harvest").value = "";
            document.getElementById("modal-expiration-date").value = "";
            document.getElementById("modal-shelf-life").value = "";
            document.getElementById("modal-storage-quantity").value = "";
            document.getElementById("modal-product-id").value = "";
            document.getElementById("modal-storage-id").value = "";
            document.getElementById("modal-vehicle-registration").value = "";
        }

        function editHarvest(harvestId) {
            // Find the harvest object from the records
            const harvest = <?php echo json_encode($harvests); ?>.find(h => h.harvestId == harvestId);

            // Populate the modal with the harvest data
            document.getElementById("modal-harvest-id").value = harvest.harvestId;
            document.getElementById("modal-farm-id").value = harvest.farmId;
            document.getElementById("modal-quantity-harvested").value = harvest.quantity_harvested;
            document.getElementById("modal-date-harvest").value = harvest.date_of_harvest;
            document.getElementById("modal-expiration-date").value = harvest.expiration_date;
            document.getElementById("modal-shelf-life").value = harvest.shelf_life;
            document.getElementById("modal-storage-quantity").value = harvest.quantity_unit_of_storage;
            document.getElementById("modal-product-id").value = harvest.productId;
            document.getElementById("modal-storage-id").value = harvest.storageId;
            document.getElementById("modal-vehicle-registration").value = harvest.vehicleRegistrationNumber;

            // Show the modal
            document.getElementById("harvestModal").classList.add("active");
            editIndex = harvestId; // Set editIndex to the harvest ID for update
        }

        function saveHarvest() {
            const harvest = {
                harvestId: document.getElementById("modal-harvest-id").value,
                farmId: document.getElementById("modal-farm-id").value,
                quantity: document.getElementById("modal-quantity-harvested").value,
                dateOfHarvest: document.getElementById("modal-date-harvest").value,
                expirationDate: document.getElementById("modal-expiration-date").value,
                shelfLife: document.getElementById("modal-shelf-life").value,
                storageQuantity: document.getElementById("modal-storage-quantity").value,
                productId: document.getElementById("modal-product-id").value,
                storageId: document.getElementById("modal-storage-id").value,
                vehicleReg: document.getElementById("modal-vehicle-registration").value,
                action: editIndex !== null ? "update" : "add",
            };

            fetch("harvest_dashboard.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(harvest),
            })
            .then((response) => response.text())
            .then((data) => {
                alert(data);
                window.location.reload();
            })
            .catch((error) => console.error("Error:", error));
        }

        function deleteHarvest(harvestId) {
            if (!confirm("Are you sure you want to delete this record?")) return;

            const harvest = {
                action: "delete",
                harvestId: harvestId,
            };

            fetch("harvest_dashboard.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(harvest),
            })
            .then((response) => response.text())
            .then((data) => {
                alert(data);
                window.location.reload();
            })
            .catch((error) => console.error("Error:", error));
        }

        function filterHarvestTable() {
            const searchValue = document.getElementById("harvest-search").value.toLowerCase();
            const rows = document.querySelectorAll("#harvest-table-body tr");

            rows.forEach((row) => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? "" : "none";
            });
        }

        function logout() {
            alert("Logged out!");
        }
    </script>
</body>
</html>
