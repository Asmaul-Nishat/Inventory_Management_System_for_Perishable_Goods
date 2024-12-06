// Vehicle list to hold active transportations
const vehicles = [];

// Add a new vehicle
function addVehicle(event) {
    event.preventDefault();

    const vehicleId = document.getElementById("vehicleId").value.trim();
    const vehicleType = document.getElementById("vehicleType").value.trim();
    const driverName = document.getElementById("driverName").value.trim();

    // Validate input
    if (vehicleId && vehicleType && driverName) {
        vehicles.push({
            id: vehicleId,
            type: vehicleType,
            driver: driverName,
            status: "Idle",
        });

        // Update the transportation table
        updateTable();

        // Clear the form
        document.getElementById("addVehicleForm").reset();
    } else {
        alert("Please fill all fields.");
    }
}

// Update the transportation table
function updateTable() {
    const tableBody = document.getElementById("transportationTable").getElementsByTagName("tbody")[0];
    tableBody.innerHTML = ""; // Clear previous rows

    vehicles.forEach(vehicle => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${vehicle.id}</td>
            <td>${vehicle.type}</td>
            <td>${vehicle.driver}</td>
            <td>${vehicle.status}</td>
            <td>
                <button onclick="updateStatus('${vehicle.id}')">Update Status</button>
                <button onclick="removeVehicle('${vehicle.id}')">Remove</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Update the status of a vehicle
function updateStatus(vehicleId) {
    const vehicle = vehicles.find(v => v.id === vehicleId);
    if (vehicle) {
        const newStatus = prompt("Enter new status (Idle, In Transit, Delivered):", vehicle.status);
        if (newStatus) {
            vehicle.status = newStatus;
            updateTable();
        }
    }
}

// Remove a vehicle from the list
function removeVehicle(vehicleId) {
    const confirmDelete = confirm(`Are you sure you want to remove Vehicle ID ${vehicleId}?`);
    if (confirmDelete) {
        const index = vehicles.findIndex(v => v.id === vehicleId);
        if (index !== -1) {
            vehicles.splice(index, 1);
            updateTable();
        }
    }
}

// Logout functionality
function logout() {
    alert("Logging out...");
    window.location.href = "mainPage.html"; // Redirect to login page
}
