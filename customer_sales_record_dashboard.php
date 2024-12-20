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

// Handle form submission for adding or updating records
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $invoiceId = $_POST['invoice-id'];
        $salesStatus = $_POST['sales-status'];
        $salesDate = $_POST['sales-date'];
        $consumerId = $_POST['consumer-id'];
        $storeId = $_POST['store-id'];

        $sql = "INSERT INTO consumer_sales_record (invoiceId, sales_status, sales_date, consumer_id, store_id)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $invoiceId, $salesStatus, $salesDate, $consumerId, $storeId);

        if ($stmt->execute()) {
            $message = "<div class='success'>Record added successfully!</div>";
        } else {
            $message = "<div class='error'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }

    if (isset($_POST['edit'])) {
        $invoiceId = $_POST['invoice-id'];
        $salesStatus = $_POST['sales-status'];
        $salesDate = $_POST['sales-date'];
        $consumerId = $_POST['consumer-id'];
        $storeId = $_POST['store-id'];

        // Debugging the received data
        var_dump($_POST); // Uncomment for debugging

        $sql = "UPDATE consumer_sales_record 
                SET sales_status=?, sales_date=?, consumer_id=?, store_id=? 
                WHERE invoiceId=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $salesStatus, $salesDate, $consumerId, $storeId, $invoiceId);

        if ($stmt->execute()) {
            // Redirect to refresh the page and show updated data
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<div class='error'>Error updating record: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM consumer_sales_record WHERE invoiceId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
    }
    $stmt->close();
}

// Fetch records
$records = [];
$sql = "SELECT invoiceId, sales_status, sales_date, consumer_id, store_id FROM consumer_sales_record";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Sales Record Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        h1 {
            color: rgb(11, 147, 11);
        }
        form {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        form select, form input, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            background-color: rgb(11, 147, 11);
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        form button:hover {
            background-color: rgb(9, 120, 9);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 5px 10px;
            text-align: left;
        }
        table th {
            background-color: rgb(11, 147, 11);
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .edit-btn, .delete-btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn {
            background-color: #FF4D4D;
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn:hover {
            background-color: #e60000;
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
            background-color: rgba(0,0,0,0.4);
            overflow: auto;
            padding-top: 60px;
        }
        .modal-content {
            background-color: #f4f4f4;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 40%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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

<div class="container">
    <h1>Consumer Sales Record Dashboard</h1>
    <?= $message ?>

    <!-- Form Section -->
    <form id="sales-form" method="POST">
        <input type="hidden" name="add">
        <label for="invoice-id">Invoice ID:</label>
        <input type="text" id="invoice-id" name="invoice-id" placeholder="Enter Invoice ID" required>

        <label for="sales-status">Sales Status:</label>
        <select id="sales-status" name="sales-status" required>
            <option value="Pending">Pending</option>
            <option value="Complete">Complete</option>
        </select>

        <label for="sales-date">Sales Date:</label>
        <input type="date" id="sales-date" name="sales-date" required>

        <label for="consumer-id">Consumer ID:</label>
        <input type="text" id="consumer-id" name="consumer-id" placeholder="Enter Consumer ID" required>

        <label for="store-id">Store ID:</label>
        <input type="text" id="store-id" name="store-id" placeholder="Enter Store ID" required>

        <button type="submit">Add Sales Record</button>
    </form>

    <!-- Records Table -->
    <table>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Sales Status</th>
                <th>Sales Date</th>
                <th>Consumer ID</th>
                <th>Store ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                    <td><?= htmlspecialchars($record['invoiceId']) ?></td>
                    <td><?= htmlspecialchars($record['sales_status']) ?></td>
                    <td><?= htmlspecialchars($record['sales_date']) ?></td>
                    <td><?= htmlspecialchars($record['consumer_id']) ?></td>
                    <td><?= htmlspecialchars($record['store_id']) ?></td>
                    <td class="action-buttons">
                        <button 
                            class="edit-btn" 
                            onclick="openEditModal(
                                '<?= $record['invoiceId'] ?>',
                                '<?= $record['sales_status'] ?>',
                                '<?= $record['sales_date'] ?>',
                                '<?= $record['consumer_id'] ?>',
                                '<?= $record['store_id'] ?>'
                            )">
                            Edit
                        </button>
                        <a href="?delete=<?= $record['invoiceId'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Editing -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Sales Record</h2>
        <form method="POST">
            <input type="hidden" name="edit">
            <input type="hidden" id="invoice-id-modal" name="invoice-id">
            <label for="sales-status-modal">Sales Status:</label>
            <select id="sales-status-modal" name="sales-status" required>
                <option value="Pending">Pending</option>
                <option value="Complete">Complete</option>
            </select>
            <label for="sales-date-modal">Sales Date:</label>
            <input type="date" id="sales-date-modal" name="sales-date">
            <label for="consumer-id-modal">Consumer ID:</label>
            <input type="text" id="consumer-id-modal" name="consumer-id">
            <label for="store-id-modal">Store ID:</label>
            <input type="text" id="store-id-modal" name="store-id">
            <button type="submit">Update</button>
        </form>
    </div>
</div>

<script>
function openEditModal(invoiceId, salesStatus, salesDate, consumerId, storeId) {
    document.getElementById("invoice-id-modal").value = invoiceId;
    document.getElementById("sales-status-modal").value = salesStatus; // Ensure this value is being set correctly
    document.getElementById("sales-date-modal").value = salesDate;
    document.getElementById("consumer-id-modal").value = consumerId;
    document.getElementById("store-id-modal").value = storeId;
    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

window.onclick = function(event) {
    if (event.target == document.getElementById("editModal")) {
        closeEditModal();
    }
}
</script>

</body>
</html>
