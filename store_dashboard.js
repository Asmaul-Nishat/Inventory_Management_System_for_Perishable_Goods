let stockData = [];
let orderData = [];

// Function to show the Stock Modal
function showStockModal() {
  document.getElementById('stockModal').style.display = 'block';
}

// Function to show the Order Modal
function showOrderModal() {
  document.getElementById('orderModal').style.display = 'block';
}

// Function to close any modal
function closeModal() {
  document.getElementById('stockModal').style.display = 'none';
  document.getElementById('orderModal').style.display = 'none';
}

// Function to save stock details to stockData array
function saveStock() {
  const productId = document.getElementById('modal-product-id').value;
  const type = document.getElementById('modal-type').value;
  const units = parseInt(document.getElementById('modal-units').value, 10);
  const price = parseFloat(document.getElementById('modal-price').value);

  if (!productId || !type || isNaN(units) || isNaN(price)) {
    alert('Please fill out all fields correctly');
    return;
  }

  const newStock = { productId, type, units, price };
  stockData.push(newStock);
  closeModal();
  renderStockTable();
}

// Function to save order details to orderData array
function saveOrder() {
  const orderId = document.getElementById('modal-order-id').value;
  const customer = document.getElementById('modal-customer').value;
  const product = document.getElementById('modal-product').value;
  const quantity = parseInt(document.getElementById('modal-quantity').value, 10);

  if (!orderId || !customer || !product || isNaN(quantity)) {
    alert('Please fill out all fields correctly');
    return;
  }

  const newOrder = { orderId, customer, product, quantity, status: 'Pending' };
  orderData.push(newOrder);
  closeModal();
  renderOrderTable();
}

// Function to render the stock table dynamically
function renderStockTable() {
  const stockTableBody = document.getElementById('stock-table-body');
  stockTableBody.innerHTML = '';

  stockData.forEach((stockItem, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${stockItem.productId}</td>
      <td>${stockItem.type}</td>
      <td>${stockItem.units}</td>
      <td>${stockItem.units > 0 ? 'In Stock' : 'Out of Stock'}</td>
      <td>${stockItem.price.toFixed(2)}</td>
      <td>
        <button onclick="editStock(${index})">Edit</button>
        <button onclick="deleteStock(${index})">Delete</button>
      </td>
    `;
    stockTableBody.appendChild(tr);
  });
}

// Function to render the order table dynamically
function renderOrderTable() {
  const orderTableBody = document.getElementById('order-table-body');
  orderTableBody.innerHTML = '';

  orderData.forEach((orderItem, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${orderItem.orderId}</td>
      <td>${orderItem.customer}</td>
      <td>${orderItem.product}</td>
      <td>${orderItem.quantity}</td>
      <td>${orderItem.status}</td>
      <td>
        <button onclick="editOrder(${index})">Edit</button>
        <button onclick="deleteOrder(${index})">Delete</button>
      </td>
    `;
    orderTableBody.appendChild(tr);
  });
}

// Function to handle editing a stock record
function editStock(index) {
  const stockItem = stockData[index];
  document.getElementById('modal-product-id').value = stockItem.productId;
  document.getElementById('modal-type').value = stockItem.type;
  document.getElementById('modal-units').value = stockItem.units;
  document.getElementById('modal-price').value = stockItem.price;

  saveStock = function () {
    const updatedType = document.getElementById('modal-type').value;
    const updatedUnits = parseInt(document.getElementById('modal-units').value, 10);
    const updatedPrice = parseFloat(document.getElementById('modal-price').value);

    stockData[index] = { productId: stockItem.productId, type: updatedType, units: updatedUnits, price: updatedPrice };
    closeModal();
    renderStockTable();
  };

  showStockModal();
}

// Function to handle editing an order record
function editOrder(index) {
  const orderItem = orderData[index];
  document.getElementById('modal-order-id').value = orderItem.orderId;
  document.getElementById('modal-customer').value = orderItem.customer;
  document.getElementById('modal-product').value = orderItem.product;
  document.getElementById('modal-quantity').value = orderItem.quantity;

  saveOrder = function () {
    const updatedCustomer = document.getElementById('modal-customer').value;
    const updatedProduct = document.getElementById('modal-product').value;
    const updatedQuantity = parseInt(document.getElementById('modal-quantity').value, 10);

    orderData[index] = {
      orderId: orderItem.orderId,
      customer: updatedCustomer,
      product: updatedProduct,
      quantity: updatedQuantity,
      status: 'Pending'
    };
    closeModal();
    renderOrderTable();
  };

  showOrderModal();
}

// Function to delete a stock item
function deleteStock(index) {
  if (confirm('Are you sure you want to delete this stock item?')) {
    stockData.splice(index, 1);
    renderStockTable();
  }
}

// Function to delete an order item
function deleteOrder(index) {
  if (confirm('Are you sure you want to delete this order?')) {
    orderData.splice(index, 1);
    renderOrderTable();
  }
}

// Function to filter the stock table
function filterStockTable() {
  const query = document.getElementById('stock-search').value.toLowerCase();
  const filteredStock = stockData.filter(
    (stock) =>
      stock.productId.toLowerCase().includes(query) ||
      stock.type.toLowerCase().includes(query)
  );
  renderFilteredStockTable(filteredStock);
}

// Function to render filtered stock table results
function renderFilteredStockTable(filteredStock) {
  const stockTableBody = document.getElementById('stock-table-body');
  stockTableBody.innerHTML = '';

  filteredStock.forEach((stockItem, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${stockItem.productId}</td>
      <td>${stockItem.type}</td>
      <td>${stockItem.units}</td>
      <td>${stockItem.units > 0 ? 'In Stock' : 'Out of Stock'}</td>
      <td>${stockItem.price.toFixed(2)}</td>
      <td>
        <button onclick="editStock(${index})">Edit</button>
        <button onclick="deleteStock(${index})">Delete</button>
      </td>
    `;
    stockTableBody.appendChild(tr);
  });
}

// Function to filter the order table
function filterOrderTable() {
  const query = document.getElementById('order-search').value.toLowerCase();
  const filteredOrders = orderData.filter(
    (order) =>
      order.orderId.toLowerCase().includes(query) ||
      order.customer.toLowerCase().includes(query)
  );
  renderFilteredOrderTable(filteredOrders);
}

// Function to render filtered order table results
function renderFilteredOrderTable(filteredOrders) {
  const orderTableBody = document.getElementById('order-table-body');
  orderTableBody.innerHTML = '';

  filteredOrders.forEach((orderItem, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${orderItem.orderId}</td>
      <td>${orderItem.customer}</td>
      <td>${orderItem.product}</td>
      <td>${orderItem.quantity}</td>
      <td>${orderItem.status}</td>
      <td>
        <button onclick="editOrder(${index})">Edit</button>
        <button onclick="deleteOrder(${index})">Delete</button>
      </td>
    `;
    orderTableBody.appendChild(tr);
  });
}
