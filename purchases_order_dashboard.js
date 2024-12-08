let purchaseOrderData = [];
let editIndex = null;

// Function to render the purchase order table
function renderPurchaseOrderTable() {
  const tableBody = document.getElementById('purchase-order-table-body');
  tableBody.innerHTML = '';

  purchaseOrderData.forEach((order, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${order.purchaseOrderId}</td>
      <td>${order.orderDate}</td>
      <td>${order.totalCost}</td>
      <td>${order.deliveryLocation}</td>
      <td>
        <button onclick="editPurchaseOrder(${index})">Edit</button>
        <button onclick="deletePurchaseOrder(${index})">Delete</button>
      </td>
    `;
    tableBody.appendChild(tr);
  });

  document.getElementById('total-orders').innerText = purchaseOrderData.length;
}

// Function to save purchase order
function savePurchaseOrder() {
  const purchaseOrderId = document.getElementById('modal-purchase-order-id').value;
  const orderDate = document.getElementById('modal-order-date').value;
  const totalCost = parseFloat(document.getElementById('modal-total-cost').value);
  const deliveryLocation = document.getElementById('modal-delivery-location').value;

  const newOrder = { purchaseOrderId, orderDate, totalCost, deliveryLocation };

  if (editIndex !== null) {
    purchaseOrderData[editIndex] = newOrder;
    editIndex = null;
  } else {
    purchaseOrderData.push(newOrder);
  }

  closeModal();
  renderPurchaseOrderTable();
}

function closeModal() {
  document.getElementById('purchaseOrderModal').style.display = 'none';
  document.getElementById('modal-purchase-order-id').value = '';
  document.getElementById('modal-order-date').value = '';
  document.getElementById('modal-total-cost').value = '';
  document.getElementById('modal-delivery-location').value = '';
}

function showPurchaseOrderModal() {
  document.getElementById('purchaseOrderModal').style.display = 'block';
}

function editPurchaseOrder(index) {
  const order = purchaseOrderData[index];
  editIndex = index;

  document.getElementById('modal-purchase-order-id').value = order.purchaseOrderId;
  document.getElementById('modal-order-date').value = order.orderDate;
  document.getElementById('modal-total-cost').value = order.totalCost;
  document.getElementById('modal-delivery-location').value = order.deliveryLocation;

  showPurchaseOrderModal();
}

function deletePurchaseOrder(index) {
  purchaseOrderData.splice(index, 1);
  renderPurchaseOrderTable();
}

window.onload = renderPurchaseOrderTable;
