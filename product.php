<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ims";

$conn = new mysqli($servername, $username, $password, $dbname);

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$query = "SELECT * FROM product";
$result = $conn->query($query);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Handle saving a product (Add or Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form fields
    if (!empty($_POST['productName']) && isset($_POST['quantityInStock']) && !empty($_POST['unitOfMeasure'])) {
        $productId = $_POST['productId'] ?? null; // This will be null for new products
        $productName = $_POST['productName'];
        $quantityInStock = $_POST['quantityInStock'];
        $unitOfMeasure = $_POST['unitOfMeasure'];

        // Check if productId is provided (it will be provided for editing an existing product)
        if ($productId && !empty($productId)) {
            // If productId is provided, update the product
            $updateQuery = "UPDATE product SET product_name = ?, quantity_in_stock = ?, unit_of_measure = ? WHERE productId = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("siis", $productName, $quantityInStock, $unitOfMeasure, $productId);
            if ($stmt->execute()) {
                header('Location: product.php'); // Redirect after successful save
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            // If no productId is provided (i.e., for adding a new product), let the DB generate the ID automatically
            $insertQuery = "INSERT INTO product (product_name, quantity_in_stock, unit_of_measure) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sis", $productName, $quantityInStock, $unitOfMeasure);
            if ($stmt->execute()) {
                header('Location: product.php'); // Redirect after successful save
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        echo "Please fill all the fields.";
    }
}

// Handle deleting a product
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];
    $deleteQuery = "DELETE FROM product WHERE productId = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $productId);
    if ($stmt->execute()) {
        header('Location: product.php'); // Redirect after delete
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product Dashboard</title>
  <link rel="stylesheet" href="product.css" />
</head>
<body>
  <!-- Wrapper -->
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Product Menu</h2>
      <button onclick="showProductModal()" class="nav-button">Add Product</button>
      <button onclick="location.href='shipment_dashboard.php'" class="nav-button">Shipment</button>
      <button onclick="location.href='mainPage.php'" class="nav-button">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <header>
        <h1>Product Dashboard</h1>
      </header>

      <div class="stats-container">
        <div class="stat-card">
          <h3>Total Products</h3>
          <p id="total-products"><?php echo count($products); ?></p>
        </div>
      </div>

      <div class="table-container">
        <h2>Product Data</h2>
        <input
          type="text"
          id="product-search"
          placeholder="Search product records..."
          onkeyup="filterProductTable()"
          class="search-bar"
        />
        <table>
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Unit of Measure</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="product-table-body">
            <?php
              foreach ($products as $product) {
                echo "<tr>
                  <td>{$product['productId']}</td>
                  <td>{$product['product_name']}</td>
                  <td>{$product['quantity_in_stock']}</td>
                  <td>{$product['unit_of_measure']}</td>
                  <td>
                    <button class='edit-btn' onclick='editProduct({$product['productId']})'>Edit</button>
                    <button class='delete-btn' onclick='deleteProduct({$product['productId']})'>Delete</button>
                  </td>
                </tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal for Product -->
  <div class="modal" id="productModal" style="display: none;">
    <h3>Add/Edit Product</h3>
    <form method="POST" action="product.php" id="productForm">
      <!-- Hidden field for editing, empty when adding a new product -->
      <input type="hidden" id="modal-product-id" name="productId" /> 
      <label>Product Name:</label>
      <input type="text" id="modal-product-name" name="productName" required />
      <label>Quantity:</label>
      <input type="number" id="modal-product-quantity" name="quantityInStock" required />
      <label>Unit of Measure:</label>
      <input type="text" id="modal-unit-of-measure" name="unitOfMeasure" required />
      <button type="submit">Save</button>
      <button type="button" onclick="closeProductModal()">Cancel</button>
    </form>
  </div>

  <script>
    let productData = <?php echo json_encode($products); ?>;

    // Function to render the product table
    function renderProductTable() {
      const productTableBody = document.getElementById('product-table-body');
      productTableBody.innerHTML = ''; // Clear existing rows

      productData.forEach((product) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${product.productId}</td>
          <td>${product.product_name}</td>
          <td>${product.quantity_in_stock}</td>
          <td>${product.unit_of_measure}</td>
          <td>
            <button class="edit-btn" onclick="editProduct(${product.productId})">Edit</button>
            <button class="delete-btn" onclick="deleteProduct(${product.productId})">Delete</button>
          </td>
        `;
        productTableBody.appendChild(tr);
      });

      document.getElementById('total-products').innerText = productData.length;
    }

    // Function to delete a product
    function deleteProduct(productId) {
      if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = `product.php?delete=${productId}`;  // Trigger the delete process via URL
      }
    }

    // Function to show the modal and populate it for editing
    function editProduct(productId) {
      const product = productData.find(p => p.productId === productId);
      document.getElementById('modal-product-id').value = product.productId;
      document.getElementById('modal-product-name').value = product.product_name;
      document.getElementById('modal-product-quantity').value = product.quantity_in_stock;
      document.getElementById('modal-unit-of-measure').value = product.unit_of_measure;

      const modal = document.getElementById('productModal');
      modal.style.display = 'block';
    }

    // Function to close the modal
    function closeProductModal() {
      document.getElementById('productModal').style.display = 'none';
    }

    // Function to show the modal for adding a new product
    function showProductModal() {
      document.getElementById('modal-product-id').value = ''; // Clear any existing data
      document.getElementById('modal-product-name').value = '';
      document.getElementById('modal-product-quantity').value = '';
      document.getElementById('modal-unit-of-measure').value = '';
      
      const modal = document.getElementById('productModal');
      modal.style.display = 'block'; // Show the modal
    }

    // Render the table on page load
    window.onload = renderProductTable;
  </script>
</body>
</html>
