// Fetch product data from PHP
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
    window.location.href = `product_dashboard.php?delete=${productId}`;  // Trigger the delete process via URL
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

// Function to save a product (Add or Edit)
function saveProduct(event) {
  event.preventDefault(); // Prevent default form submission behavior

  // Check if the product name is not empty
  const productName = document.getElementById('modal-product-name').value.trim();
  if (!productName) {
    alert('Product name is required.');
    return;
  }

  const form = document.getElementById('productForm');
  form.submit(); // Submit the form to PHP for processing
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

// Function to filter products in the table
function filterProductTable() {
  const searchTerm = document.getElementById('product-search').value.toLowerCase();
  const filteredData = productData.filter(product => 
    product.product_name.toLowerCase().includes(searchTerm) || 
    product.unit_of_measure.toLowerCase().includes(searchTerm)
  );
  
  productData = filteredData;
  renderProductTable();
}

// Render the table on page load
window.onload = renderProductTable;
