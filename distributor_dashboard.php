<?php
// Include your database connection
include 'practice.php';

// Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add a new distributor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $distributorName = $_POST['distributorName'];
    $distributorAddress = $_POST['distributorAddress'];
    $typeOfGoods = $_POST['typeOfGoods'];
    $quantityPurchased = $_POST['quantityPurchased'];
    $purchaseDate = $_POST['purchaseDate'];

    // SQL query to insert a new distributor
    $sql = "INSERT INTO distributor (distributor_name, distributor_address, type_of_goods_purchased, quantity_purchased, purchase_date) 
            VALUES ('$distributorName', '$distributorAddress', '$typeOfGoods', '$quantityPurchased', '$purchaseDate')";

    if ($conn->query($sql) === TRUE) {
        echo "New distributor added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle edit distributor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $distributorId = $_POST['distributorId'];
    $distributorName = $_POST['distributorName'];
    $distributorAddress = $_POST['distributorAddress'];
    $typeOfGoods = $_POST['typeOfGoods'];
    $quantityPurchased = $_POST['quantityPurchased'];
    $purchaseDate = $_POST['purchaseDate'];

    // SQL query to update the distributor
    $sql = "UPDATE distributor SET 
            distributor_name = '$distributorName',
            distributor_address = '$distributorAddress',
            type_of_goods_purchased = '$typeOfGoods',
            quantity_purchased = '$quantityPurchased',
            purchase_date = '$purchaseDate'
            WHERE distributorId = $distributorId";

    if ($conn->query($sql) === TRUE) {
        echo "Distributor updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// SQL query to fetch all distributor data
$sql = "SELECT distributorId, distributor_name, distributor_address, type_of_goods_purchased, quantity_purchased, purchase_date FROM distributor";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distributor Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .dashboard-container {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: rgb(11, 147, 11);
            color: white;
        }
        .btn {
            padding: 10px 20px;
            background-color: rgb(11, 147, 11);
            color: white;
            border: none;
            cursor: pointer;
            margin: 10px 0;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-primary {
            background-color: rgb(11, 147, 11);
        }
        .form-container {
            margin-top: 20px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
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
            background-color: rgb(11, 147, 11);
            color: white;
            padding: 10px;
            font-size: 18px;
        }

        .modal-footer {
            text-align: right;
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

<div class="dashboard-container">

    <!-- Distributor Table -->
    <h2>Distributor Information</h2>
    <div>  
      <button onclick="location.href='purchases_order_dashboard.php'" class="nav-button">Purchase Order</button>
      <button onclick="location.href='shipment_dashboard.php'" class="nav-button">Shipment</button>
      <button onclick="location.href='store_dashboard.php'" class="nav-button">Store</button>
      <button onclick="location.href='mainPage.php'" class="nav-button">Logout</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>Distributor ID</th>
                <th>Distributor Name</th>
                <th>Distributor Address</th>
                <th>Type of Goods Purchased</th>
                <th>Quantity Purchased</th>
                <th>Purchase Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Loop through the result and display each row in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['distributorId']}</td>
                            <td>{$row['distributor_name']}</td>
                            <td>{$row['distributor_address']}</td>
                            <td>{$row['type_of_goods_purchased']}</td>
                            <td>{$row['quantity_purchased']}</td>
                            <td>{$row['purchase_date']}</td>
                            <td>
                                <button class='btn btn-primary' onclick='editDistributor({$row['distributorId']}, \"{$row['distributor_name']}\", \"{$row['distributor_address']}\", \"{$row['type_of_goods_purchased']}\", {$row['quantity_purchased']}, \"{$row['purchase_date']}\")'>Edit</button>
                                <button class='btn btn-danger' onclick='removeDistributor({$row['distributorId']})'>Remove</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Add Distributor Form -->
    <div class="form-container">
        <h3>Add New Distributor</h3>
        <form action="" method="POST">
            <input type="text" name="distributorName" placeholder="Distributor Name" required><br>
            <input type="text" name="distributorAddress" placeholder="Distributor Address" required><br>
            <input type="text" name="typeOfGoods" placeholder="Type of Goods Purchased" required><br>
            <input type="number" name="quantityPurchased" placeholder="Quantity Purchased" required><br>
            <input type="date" name="purchaseDate" required><br>
            <button type="submit" name="add" class="btn">Add Distributor</button>
        </form>
    </div>

    <!-- Edit Distributor Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Edit Distributor</h2>
            </div>
            <form id="editDistributorForm" method="POST">
                <input type="hidden" name="distributorId" id="editDistributorId">
                <input type="text" name="distributorName" id="editDistributorName" placeholder="Distributor Name" required><br>
                <input type="text" name="distributorAddress" id="editDistributorAddress" placeholder="Distributor Address" required><br>
                <input type="text" name="typeOfGoods" id="editTypeOfGoods" placeholder="Type of Goods Purchased" required><br>
                <input type="number" name="quantityPurchased" id="editQuantityPurchased" placeholder="Quantity Purchased" required><br>
                <input type="date" name="purchaseDate" id="editPurchaseDate" required><br>
                <div class="modal-footer">
                    <button type="submit" name="edit" class="btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Function to handle editing a distributor
    function editDistributor(id, name, address, goods, quantity, date) {
        document.getElementById('editDistributorId').value = id;
        document.getElementById('editDistributorName').value = name;
        document.getElementById('editDistributorAddress').value = address;
        document.getElementById('editTypeOfGoods').value = goods;
        document.getElementById('editQuantityPurchased').value = quantity;
        document.getElementById('editPurchaseDate').value = date;
        
        // Show the modal
        document.getElementById('editModal').style.display = 'block';
    }

    // Close the modal
    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Function to handle removing a distributor
    function removeDistributor(id) {
        if (confirm('Are you sure you want to delete this distributor?')) {
            window.location.href = `?delete_id=${id}`;
        }
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
