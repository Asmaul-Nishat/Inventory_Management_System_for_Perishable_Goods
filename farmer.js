let farmerData = [];
let editIndex = null;

// Function to render the farmer table
function renderFarmerTable() {
  const tableBody = document.getElementById('farmer-table-body');
  tableBody.innerHTML = '';

  farmerData.forEach((farmer, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${farmer.organizationId}</td>
      <td>${farmer.organizationName}</td>
      <td>${farmer.licenseNumber}</td>
      <td>${farmer.farmLocation}</td>
      <td>
        <button onclick="editFarmer(${index})">Edit</button>
        <button onclick="deleteFarmer(${index})">Delete</button>
      </td>
    `;
    tableBody.appendChild(tr);
  });

  document.getElementById('total-farmers').innerText = farmerData.length;
}

// Function to save farmer data
function saveFarmer() {
  const organizationId = document.getElementById('modal-organization-id').value;
  const organizationName = document.getElementById('modal-organization-name').value;
  const licenseNumber = document.getElementById('modal-license-number').value;
  const farmLocation = document.getElementById('modal-farm-location').value;

  const newFarmer = { organizationId, organizationName, licenseNumber, farmLocation };

  if (editIndex !== null) {
    farmerData[editIndex] = newFarmer;
    editIndex = null;
  } else {
    farmerData.push(newFarmer);
  }

  closeModal();
  renderFarmerTable();
}

function closeModal() {
  document.getElementById('farmerModal').style.display = 'none';
  document.getElementById('modal-organization-id').value = '';
  document.getElementById('modal-organization-name').value = '';
  document.getElementById('modal-license-number').value = '';
  document.getElementById('modal-farm-location').value = '';
}

function showFarmerModal() {
  document.getElementById('farmerModal').style.display = 'block';
}

function editFarmer(index) {
  const farmer = farmerData[index];
  editIndex = index;

  document.getElementById('modal-organization-id').value = farmer.organizationId;
  document.getElementById('modal-organization-name').value = farmer.organizationName;
  document.getElementById('modal-license-number').value = farmer.licenseNumber;
  document.getElementById('modal-farm-location').value = farmer.farmLocation;

  showFarmerModal();
}

function deleteFarmer(index) {
  farmerData.splice(index, 1);
  renderFarmerTable();
}

window.onload = renderFarmerTable;
