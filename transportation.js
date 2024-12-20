let vehicleData = [];
let editingIndex = null; // Track the index being edited

// Function to render the vehicle table
function renderVehicleTable() {
  const vehicleTableBody = document.getElementById('vehicle-table-body');
  vehicleTableBody.innerHTML = '';

  vehicleData.forEach((vehicle, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${vehicle.vehicleRegNumber}</td>
      <td>${vehicle.manufacturingYear}</td>
      <td>${vehicle.mileage}</td>
      <td>${vehicle.lastServicingDate}</td>
      <td>
        <button onclick="editVehicle(${index})">Edit</button>
        <button onclick="deleteVehicle(${index})">Delete</button>
      </td>
    `;
    vehicleTableBody.appendChild(tr);
  });

  document.getElementById('total-vehicles').innerText = vehicleData.length;
}

// Function to save or update a vehicle
function saveVehicle() {
  const vehicleRegNumber = document.getElementById('modal-vehicle-reg').value;
  const manufacturingYear = parseInt(document.getElementById('modal-manufacturing-year').value);
  const mileage = parseInt(document.getElementById('modal-mileage').value);
  const lastServicingDate = document.getElementById('modal-servicing-date').value;

  if (editingIndex !== null) {
    // Update existing vehicle
    vehicleData[editingIndex] = {
      vehicleRegNumber,
      manufacturingYear,
      mileage,
      lastServicingDate,
    };
    editingIndex = null; // Reset editing index
  } else {
    // Add new vehicle
    const newVehicle = {
      vehicleRegNumber,
      manufacturingYear,
      mileage,
      lastServicingDate,
    };
    vehicleData.push(newVehicle);
  }

  closeModal();
  renderVehicleTable();
}

// Function to populate the modal with existing data for editing
function editVehicle(index) {
  const vehicle = vehicleData[index];
  document.getElementById('modal-vehicle-reg').value = vehicle.vehicleRegNumber;
  document.getElementById('modal-manufacturing-year').value = vehicle.manufacturingYear;
  document.getElementById('modal-mileage').value = vehicle.mileage;
  document.getElementById('modal-servicing-date').value = vehicle.lastServicingDate;

  editingIndex = index; // Set the editing index
  showTransportationModal();
}

// Modal functions
function closeModal() {
  document.getElementById('transportationModal').style.display = 'none';
  resetModal();
}

function showTransportationModal() {
  document.getElementById('transportationModal').style.display = 'block';
}

// Function to reset modal inputs
function resetModal() {
  document.getElementById('modal-vehicle-reg').value = '';
  document.getElementById('modal-manufacturing-year').value = '';
  document.getElementById('modal-mileage').value = '';
  document.getElementById('modal-servicing-date').value = '';
  editingIndex = null; // Reset editing index to prevent unintended edits
}

// Function to delete a vehicle
function deleteVehicle(index) {
  vehicleData.splice(index, 1);
  renderVehicleTable();
}

// Initialize the table on load
window.onload = renderVehicleTable;



