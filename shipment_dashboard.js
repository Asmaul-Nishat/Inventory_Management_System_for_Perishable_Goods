let shipmentData = [];
let editingIndex = null; // Track the editing index

// Function to render the shipment table
function renderShipmentTable() {
  const shipmentTableBody = document.getElementById('shipment-table-body');
  shipmentTableBody.innerHTML = '';

  shipmentData.forEach((shipment, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${shipment.shipmentId}</td>
      <td>${shipment.shipmentDate}</td>
      <td>${shipment.deliveryDate}</td>
      <td>${shipment.quantity}</td>
      <td>${shipment.loadingDate} ${shipment.loadingTime}</td>
      <td>
        <button onclick="editShipment(${index})">Edit</button>
        <button onclick="deleteShipment(${index})">Delete</button>
      </td>
    `;
    shipmentTableBody.appendChild(tr);
  });

  document.getElementById('total-shipments').innerText = shipmentData.length;
}

// Function to save or update a shipment
function saveShipment() {
  const shipmentId = document.getElementById('modal-shipment-id').value;
  const shipmentDate = document.getElementById('modal-shipment-date').value;
  const deliveryDate = document.getElementById('modal-delivery-date').value;
  const quantity = parseInt(document.getElementById('modal-shipment-quantity').value);
  const loadingDate = document.getElementById('modal-loading-date').value;
  const loadingTime = document.getElementById('modal-loading-time').value;

  if (editingIndex !== null) {
    // Update existing shipment
    shipmentData[editingIndex] = {
      shipmentId,
      shipmentDate,
      deliveryDate,
      quantity,
      loadingDate,
      loadingTime,
    };
    editingIndex = null;
  } else {
    // Add new shipment
    shipmentData.push({
      shipmentId,
      shipmentDate,
      deliveryDate,
      quantity,
      loadingDate,
      loadingTime,
    });
  }

  closeModal();
  renderShipmentTable();
}

// Function to edit a shipment
function editShipment(index) {
  const shipment = shipmentData[index];
  document.getElementById('modal-shipment-id').value = shipment.shipmentId;
  document.getElementById('modal-shipment-date').value = shipment.shipmentDate;
  document.getElementById('modal-delivery-date').value = shipment.deliveryDate;
  document.getElementById('modal-shipment-quantity').value = shipment.quantity;
  document.getElementById('modal-loading-date').value = shipment.loadingDate;
  document.getElementById('modal-loading-time').value = shipment.loadingTime;

  editingIndex = index;
  showShipmentModal();
}

// Modal functions
function closeModal() {
  document.getElementById('shipmentModal').style.display = 'none';
  resetModal();
}

function showShipmentModal() {
  document.getElementById('shipmentModal').style.display = 'block';
}

function resetModal() {
  document.getElementById('modal-shipment-id').value = '';
  document.getElementById('modal-shipment-date').value = '';
  document.getElementById('modal-delivery-date').value = '';
  document.getElementById('modal-shipment-quantity').value = '';
  document.getElementById('modal-loading-date').value = '';
  document.getElementById('modal-loading-time').value = '';
  editingIndex = null;
}

// Function to delete a shipment
function deleteShipment(index) {
  shipmentData.splice(index, 1);
  renderShipmentTable();
}

// Initialize the table on load
window.onload = renderShipmentTable;
