<?php
// Database connection
$host = '127.0.0.1';
$db = 'ims';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distributors for the dropdown
$sql_distributors = "SELECT distributorId, distributor_name FROM distributor";
$distributors = $conn->query($sql_distributors);
if (!$distributors) {
    die("Query failed: " . $conn->error);
}

// Handle form submission for adding/updating a store
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_id = $_POST['store_id'] ?? null;
    $name = $_POST['name'];
    $store_contact_number = $_POST['store_contact_number'];
    $store_address = $_POST['store_address'];
    $store_website_URL = $_POST['store_website_URL'];
    $distributor_id = $_POST['distributor_id'];

    if ($store_id) {
        // Update existing store
        $sql = "UPDATE store SET store_name = ?, store_contact_number = ?, store_address = ?, store_website_URL = ?, distributor_id = ? WHERE storeId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $name, $store_contact_number, $store_address, $store_website_URL, $distributor_id, $store_id);
    } else {
        // Insert new store
        $sql = "INSERT INTO store (store_name, store_contact_number, store_address, store_website_URL, distributor_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $store_contact_number, $store_address, $store_website_URL, $distributor_id);
    }

    if ($stmt->execute()) {
        echo $store_id ? "Store Updated Successfully!" : "Store Added Successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle delete request
if (isset($_GET['delete'])) {
    $store_id = $_GET['delete'];

    $sql_delete = "DELETE FROM store WHERE storeId = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $store_id);

    if ($stmt->execute()) {
        echo "Store Deleted Successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch stores for the table
$sql_stores = "SELECT s.storeId, s.store_name, s.store_contact_number, s.store_address, s.store_website_URL, d.distributor_name, d.distributorId
               FROM store s
               LEFT JOIN distributor d ON s.distributor_id = d.distributorId";
$stores = $conn->query($sql_stores);
if (!$stores) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Store Dashboard</title>
    <link rel="stylesheet" href="store_dashboard.css" />

    <style>
    #stockModal {
        height: 200px;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        width: 350px;
        max-width: 100%;
        display:none;
    }

    #stockModal.show {
        display: block;
    }
    </style>

</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Store Menu</h2>
            <button onclick="showStockModal()" class="nav-button">Add Store</button>
            <button onclick="location.href='storage_sensor_data.php'" class="nav-button">Sensor Data</button>
            <button onclick="location.href='shipment_dashboard.php'" class="nav-button">Shipment</button>
            <button onclick="location.href='customer_sales_record_dashboard.php'" class="nav-button">Consumer Sales Record</button>
            <button onclick="location.href='distributor_dashboard.php'" class="nav-button">Distributor</button>
            <button onclick="location.href='mainPage'" class="nav-button">Logout</button>
        </div>

        <div class="main-content">
            <!-- <header>
                <h1>Store Dashboard</h1>
            </header> -->

            <!-- <div class="stats-container">
                <div class="stat-card">
                    <h3>Today's Sales</h3>
                    <p id="today-sales">0</p>
                </div>
                <div class="stat-card">
                    <h3>Stock Overview</h3>
                    <p id="stock-overview">0</p>
                </div>
                <div class="stat-card">
                    <h3>Orders Pending</h3>
                    <p id="orders-pending">0</p>
                </div>
            </div> -->

            <div class="content-container">
                <div>
                    <h1>Store Dashboard</h1>

                    <table>
                        <thead>
                            <tr>
                                <th>Store ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Website</th>
                                <th>Distributor</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stores->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['storeId']; ?></td>
                                <td><?php echo $row['store_name']; ?></td>
                                <td><?php echo $row['store_contact_number']; ?></td>
                                <td><?php echo $row['store_address']; ?></td>
                                <td><?php echo $row['store_website_URL']; ?></td>
                                <td><?php echo $row['distributor_name'] ?? 'N/A'; ?></td>
                                <td>
                                    <button
                                        onclick="editStore(<?php echo $row['storeId']; ?>, '<?php echo $row['store_name']; ?>', '<?php echo $row['store_contact_number']; ?>', '<?php echo $row['store_address']; ?>', '<?php echo $row['store_website_URL']; ?>', <?php echo $row['distributorId']; ?>)">Edit</button>
                                    <a href="?delete=<?php echo $row['storeId']; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Modal for Adding/Editing a Store -->
    <!-- Store Modal for Adding/Editing a Store -->
    <div id="stockModal">
        <form action="store_dashboard.php" method="POST" onsubmit="hideStockModal()">
            <input type="hidden" name="store_id" id="modal-store-id">
            <label>Name:</label>
            <input type="text" name="name" id="modal-name" required>
            <label>Contact:</label>
            <input type="text" name="store_contact_number" id="modal-contact" required>
            <label>Address:</label>
            <input type="text" name="store_address" id="modal-address" required>
            <label>Website URL:</label>
            <input type="text" name="store_website_URL" id="modal-website" required>
            <label>Distributor:</label>
            <select name="distributor_id" id="modal-distributor" required>
                <option value="">Select Distributor</option>
                <?php 
            $distributors->data_seek(0); // Reset pointer for re-use
            while ($row = $distributors->fetch_assoc()) : ?>
                <option value="<?php echo $row['distributorId']; ?>"><?php echo $row['distributor_name']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Save</button>
        </form>
    </div>

    <script>
    // Show the modal when the "Add Store" button is clicked
    function showStockModal() {
        const modal = document.getElementById('stockModal');
        modal.classList.add('show'); // Show modal
        resetForm(); // Reset form fields when the modal is opened
    }

    // Pre-fill the modal with store details for editing
    function editStore(store_id, name, contact, address, website, distributorId) {
        const modal = document.getElementById('stockModal');
        modal.classList.add('show'); // Show modal with existing data
        document.getElementById('modal-store-id').value = store_id;
        document.getElementById('modal-name').value = name;
        document.getElementById('modal-contact').value = contact;
        document.getElementById('modal-address').value = address;
        document.getElementById('modal-website').value = website;
        document.getElementById('modal-distributor').value = distributorId;
    }

    // Reset the form fields
    function resetForm() {
        document.getElementById('modal-store-id').value = '';
        document.getElementById('modal-name').value = '';
        document.getElementById('modal-contact').value = '';
        document.getElementById('modal-address').value = '';
        document.getElementById('modal-website').value = '';
        document.getElementById('modal-distributor').value = '';
    }

    // Hide the modal after form submission
    function hideStockModal() {
        const modal = document.getElementById('stockModal');
        modal.classList.remove('show'); // Hide the modal
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('stockModal');
        if (event.target === modal) {
            modal.classList.remove('show');
        }
    };

    // Close modal with the ESC key
    document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('stockModal');
        if (event.key === "Escape") {
            modal.classList.remove('show');
        }
    });

    function hideStockModal() {
        const modal = document.getElementById('stockModal');
        modal.classList.remove('show'); // Hide the modal
        return false; // Prevent form submission to handle redirection in a real-world scenario
    }
    </script>

</body>

</html>