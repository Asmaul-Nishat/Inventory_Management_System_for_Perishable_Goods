let harvestData = [];

// Function to render the harvest table
function renderHarvestTable() {
  const harvestTableBody = document.getElementById('harvest-table-body');
  harvestTableBody.innerHTML = '';

  harvestData.forEach((harvestItem, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${harvestItem.harvestId}</td>
      <td>${harvestItem.quantityHarvested}</td>
      <td>${harvestItem.dateOfHarvest}</td>
      <td>${harvestItem.grade}</td>
      <td>${harvestItem.expirationDate}</td>
      <td>${harvestItem.shelfLife}</td>
      <td>${harvestItem.quantityUnitToStorage}</td>
      <td>
        <button onclick="editHarvest(${index})">Edit</button>
        <button onclick="deleteHarvest(${index})">Delete</button>
      </td>
    `;
    harvestTableBody.appendChild(tr);
  });

  document.getElementById('total-harvests').innerText = harvestData.length;
}

// Function to handle saving (Add/Edit) harvest data
function saveHarvest() {
  const harvestId = document.getElementById('modal-harvest-id').value;
  const quantityHarvested = parseInt(document.getElementById('modal-quantity-harvested').value);
  const dateOfHarvest = document.getElementById('modal-date-harvest').value;
  const grade = document.getElementById('modal-grade').value;
  const expirationDate = document.getElementById('modal-expiration-date').value;
  const shelfLife = parseInt(document.getElementById('modal-shelf-life').value);
  const quantityUnitToStorage = parseInt(document.getElementById('modal-storage-quantity').value);

  const newHarvest = {
    harvestId,
    quantityHarvested,
    dateOfHarvest,
    grade,
    expirationDate,
    shelfLife,
    quantityUnitToStorage
  };

  const modal = document.getElementById('harvestModal');
  const editIndex = parseInt(modal.dataset.editIndex);

  if (isNaN(editIndex)) {
    // If not editing, add a new entry
    harvestData.push(newHarvest);
  } else {
    // If editing, update the existing entry
    harvestData[editIndex] = newHarvest;
    modal.dataset.editIndex = ''; // Clear the edit index after saving
  }

  closeModal();
  renderHarvestTable();
}

// Function to show the modal for adding a new harvest
function showHarvestModal() {
  clearModalFields();
  document.getElementById('harvestModal').style.display = 'block';
  const modal = document.getElementById('harvestModal');
  modal.dataset.editIndex = ''; // Clear the edit index for new entries
}

// Function to clear modal fields
function clearModalFields() {
  document.getElementById('modal-harvest-id').value = '';
  document.getElementById('modal-quantity-harvested').value = '';
  document.getElementById('modal-date-harvest').value = '';
  document.getElementById('modal-grade').value = '';
  document.getElementById('modal-expiration-date').value = '';
  document.getElementById('modal-shelf-life').value = '';
  document.getElementById('modal-storage-quantity').value = '';
}

// Function to close the modal
function closeModal() {
  document.getElementById('harvestModal').style.display = 'none';
}

// Function to delete a harvest
function deleteHarvest(index) {
  harvestData.splice(index, 1);
  renderHarvestTable();
}

// Function to edit a harvest
function editHarvest(index) {
  const harvestItem = harvestData[index];

  // Populate the modal fields with the data to be edited
  document.getElementById('modal-harvest-id').value = harvestItem.harvestId;
  document.getElementById('modal-quantity-harvested').value = harvestItem.quantityHarvested;
  document.getElementById('modal-date-harvest').value = harvestItem.dateOfHarvest;
  document.getElementById('modal-grade').value = harvestItem.grade;
  document.getElementById('modal-expiration-date').value = harvestItem.expirationDate;
  document.getElementById('modal-shelf-life').value = harvestItem.shelfLife;
  document.getElementById('modal-storage-quantity').value = harvestItem.quantityUnitToStorage;

  // Set the edit index
  const modal = document.getElementById('harvestModal');
  modal.dataset.editIndex = index;

  // Show the modal
  document.getElementById('harvestModal').style.display = 'block';
}

window.onload = renderHarvestTable;
