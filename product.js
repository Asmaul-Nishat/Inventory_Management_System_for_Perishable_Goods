let productData = [];

// Function to render the product table
function renderProductTable() {
  const productTableBody = document.getElementById('product-table-body');
  productTableBody.innerHTML = '';

  productData.forEach((product, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${product.productId}</td>
      <td>${product.productName}</td>
      <td>${product.quantity}</td>
      <td>${product.storageLocation}</td>
      <td>${product.expirationDate}</td>
      <td>
        <button onclick="editProduct(${index})">Edit</button>
        <button onclick="deleteProduct(${index})">Delete</button>
      </td>
    `;
    productTableBody.appendChild(tr);
  });

  document.getElementById('total-products').innerText = productData.length;
}

// Function to save a product
function saveProduct() {
  const productId = document.getElementById('modal-product-id').value;
  const productName = document.getElementById('modal-product-name').value;
  const quantity = parseInt(document.getElementById('modal-product-quantity').value);
  const storageLocation = document.getElementById('modal-storage-location').value;
  const expirationDate = document.getElementById('modal-expiration-date').value;

  const newProduct = { productId, productName, quantity, storageLocation, expirationDate };

  const modal = document.getElementById('productModal');
  const editIndex = parseInt(modal.dataset.editIndex);

  if (isNaN(editIndex)) {
    productData.push(newProduct);
  } else {
    productData[editIndex] = newProduct;
    modal.dataset.editIndex = '';
  }

  closeProductModal();
  renderProductTable();
}

// Function to edit a product
function editProduct(index) {
  const product = productData[index];
  document.getElementById('modal-product-id').value = product.productId;
  document.getElementById('modal-product-name').value = product.productName;
  document.getElementById('modal-product-quantity').value = product.quantity;
  document.getElementById('modal-storage-location').value = product.storageLocation;
  document.getElementById('modal-expiration-date').value = product.expirationDate;

  const modal = document.getElementById('productModal');
  modal.dataset.editIndex = index;

  modal.style.display = 'block';
}

// Function to delete a product
function deleteProduct(index) {
  productData.splice(index, 1);
  renderProductTable();
}

// Function to close the modal
function closeProductModal() {
  document.getElementById('productModal').style.display = 'none';
}

// Function to show the modal
function showProductModal() {
  clearModalFields();
  document.getElementById('productModal').style.display = 'block';
}

// Function to clear modal fields
function clearModalFields() {
  document.getElementById('modal-product-id').value = '';
  document.getElementById('modal-product-name').value = '';
  document.getElementById('modal-product-quantity').value = '';
  document.getElementById('modal-storage-location').value = '';
  document.getElementById('modal-expiration-date').value = '';
}

// Render the table on page load
window.onload = renderProductTable;
