<?php
// Include your database connection
include 'practice.php';

// Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add a new storage item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $storageLocation = $_POST['storage_location'];
    $storageCapacity = $_POST['storage_capacity'];
    $storageName = $_POST['storage_name'];
    $storageContactNumber = $_POST['storage_contact_number'];
    $distributorId = $_POST['distributor_id'];

    // SQL query to insert a new storage item
    $sql = "INSERT INTO storage (storage_location, storage_capacity, storage_name, storage_contact_number, distributor_id) 
            VALUES ('$storageLocation', '$storageCapacity', '$storageName', '$storageContactNumber', '$distributorId')";

    if ($conn->query($sql) === TRUE) {
        echo "New storage item added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle edit storage item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $storageId = $_POST['storageId'];
    $storageLocation = $_POST['storage_location'];
    $storageCapacity = $_POST['storage_capacity'];
    $storageName = $_POST['storage_name'];
    $storageContactNumber = $_POST['storage_contact_number'];
    $distributorId = $_POST['distributor_id'];

    // SQL query to update the storage item
    $sql = "UPDATE storage SET 
            storage_location = '$storageLocation',
            storage_capacity = '$storageCapacity',
            storage_name = '$storageName',
            storage_contact_number = '$storageContactNumber',
            distributor_id = '$distributorId'
            WHERE storageId = $storageId";

    if ($conn->query($sql) === TRUE) {
        echo "Storage item updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle remove storage item
if (isset($_GET['storageId'])) {
    $storageId = $_GET['storageId'];
    $sql = "DELETE FROM storage WHERE storageId = $storageId";

    if ($conn->query($sql) === TRUE) {
        echo "Storage item removed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: storage.php"); // Redirect to avoid repeated deletion
    exit;
}

// SQL query to fetch all storage item data
$sql = "SELECT storageId, storage_location, storage_capacity, storage_name, storage_contact_number, distributor_id FROM storage";
$result = $conn->query($sql);

// SQL query to fetch distributor data
$sql_distributors = "SELECT distributorId, distributor_name FROM distributor";
$result_distributors = $conn->query($sql_distributors);
$distributors = [];
if ($result_distributors->num_rows > 0) {
    while ($row = $result_distributors->fetch_assoc()) {
        $distributors[$row['distributorId']] = $row['distributor_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Storage Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .navigation ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            background-color: #333;
        }
        .navigation li {
            margin: 10px;
        }
        .navigation a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }
        .navigation a:hover {
            background-color: #575757;
        }
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin: 10px 0;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .form-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }
        .modal-header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            font-size: 18px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Farm Storage Management</h1>
</div>

<div class="navigation">
    <ul>
        <li><a href="shipment_dashboard.php">Shipment</a></li>
        <li><a href="store_dashboard.php">Store</a></li>
        <li><a href="storage_sensor_data.php">Storage Sensor Data</a></li>
        <li><a href="customer_sales_record_dashboard.php">Consumer Sales Record</a></li>
        <li><a href="mainPage.php">Logout</a></li>
    </ul>
</div>

<div class="dashboard-container">

    <!-- Storage Table -->
    <h2>Storage Information</h2>
    <table>
        <thead>
            <tr>
                <th>Storage ID</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Distributor ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['storageId']}</td>
                            <td>{$row['storage_location']}</td>
                            <td>{$row['storage_capacity']}</td>
                            <td>{$row['storage_name']}</td>
                            <td>{$row['storage_contact_number']}</td>
                            <td>{$row['distributor_id']}</td>
                            <td>
                                <button class='btn btn-primary' onclick='editStorage({$row['storageId']}, \"{$row['storage_location']}\", {$row['storage_capacity']}, \"{$row['storage_name']}\", \"{$row['storage_contact_number']}\", {$row['distributor_id']})'>Edit</button>
                                <button class='btn btn-danger' onclick='removeStorage({$row['storageId']})'>Remove</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Add Storage Item Form -->
    <div class="form-container">
        <h3>Add New Storage Item</h3>
        <form action="" method="POST">
            <input type="text" name="storage_location" placeholder="Storage Location" required><br>
            <input type="number" name="storage_capacity" placeholder="Storage Capacity" required><br>
            <input type="text" name="storage_name" placeholder="Storage Name" required><br>
            <input type="text" name="storage_contact_number" placeholder="Storage Contact Number" required><br>
            <select name="distributor_id" required>
                <option value="">Select Distributor ID</option>
                <?php
                foreach ($distributors as $id => $name) {
                    echo "<option value=\"$id\">$id</option>";
                }
                ?>
            </select><br>
            <button type="submit" name="add" class="btn">Add Storage Item</button>
        </form>
    </div>

    <!-- Edit Storage Item Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Edit Storage Item</h2>
            </div>
            <form id="editStorageForm" method="POST">
                <input type="hidden" name="storageId" id="editStorageId">
                <input type="text" name="storage_location" id="editStorageLocation" placeholder="Storage Location" required><br>
                <input type="number" name="storage_capacity" id="editStorageCapacity" placeholder="Storage Capacity" required><br>
                <input type="text" name="storage_name" id="editStorageName" placeholder="Storage Name" required><br>
                <input type="text" name="storage_contact_number" id="editStorageContactNumber" placeholder="Storage Contact Number" required><br>
                <select name="distributor_id" id="editDistributorId" required>
                    <option value="">Select Distributor ID</option>
                    <?php
                    foreach ($distributors as $id => $name) {
                        echo "<option value=\"$id\">$id</option>";
                    }
                    ?>
                </select><br>
                <button type="submit" name="edit" class="btn">Save Changes</button>
            </form>
        </div>
    </div>

</div>

<script>
    // Function to handle editing a storage item
    function editStorage(id, location, capacity, name, contact, distributorId) {
        document.getElementById('editStorageId').value = id;
        document.getElementById('editStorageLocation').value = location;
        document.getElementById('editStorageCapacity').value = capacity;
        document.getElementById('editStorageName').value = name;
        document.getElementById('editStorageContactNumber').value = contact;
        document.getElementById('editDistributorId').value = distributorId;

        // Show the modal
        document.getElementById('editModal').style.display = 'block';
    }

    // Close the modal
    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Function to handle removing a storage item
    function removeStorage(id) {
        if (confirm("Are you sure you want to remove this storage item?")) {
            window.location.href = "storage.php?storageId=" + id;
        }
    }
</script>

</body>
</html>
