// Sample data
const storageData = [
    { id: 1, crop: "Wheat", quantity: 500, capacity: 1000, lastUpdated: "2024-11-27" },
    { id: 2, crop: "Rice", quantity: 300, capacity: 700, lastUpdated: "2024-11-28" },
];

// Chart setup
function updateChart() {
    const totalCapacity = storageData.reduce((sum, item) => sum + item.capacity, 0);
    const totalQuantity = storageData.reduce((sum, item) => sum + item.quantity, 0);

    const ctx = document.getElementById("storageChart").getContext("2d");
    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Used Capacity", "Available Capacity"],
            datasets: [
                {
                    data: [totalQuantity, totalCapacity - totalQuantity],
                    backgroundColor: ["#007bff", "#28a745"],
                },
            ],
        },
        options: { responsive: false },
    });
}

// Render table
function renderTable() {
    const tableBody = document.getElementById("storage-table-body");
    tableBody.innerHTML = "";

    storageData.forEach((item) => {
        const row = `
            <tr>
                <td>${item.id}</td>
                <td>${item.crop}</td>
                <td>${item.quantity}</td>
                <td>${item.capacity}</td>
                <td>${item.lastUpdated}</td>
                <td>
                    <button class="btn" onclick="editStorage(${item.id})">Edit</button>
                    <button class="btn" onclick="deleteStorage(${item.id})">Delete</button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
    updateChart();
}

// Add/Edit Storage
let editId = null;
function openAddModal() {
    document.getElementById("storage-modal").style.display = "flex";
    document.getElementById("modal-title").innerText = "Add Storage Data";
    document.getElementById("storage-form").reset();
    editId = null;
}

function editStorage(id) {
    const storage = storageData.find((item) => item.id === id);
    document.getElementById("storage-modal").style.display = "flex";
    document.getElementById("modal-title").innerText = "Edit Storage Data";

    document.getElementById("crop-name").value = storage.crop;
    document.getElementById("stored-quantity").value = storage.quantity;
    document.getElementById("max-capacity").value = storage.capacity;

    editId = id;
}

function closeModal() {
    document.getElementById("storage-modal").style.display = "none";
}

document.getElementById("storage-form").addEventListener("submit", (event) => {
    event.preventDefault();

    const cropName = document.getElementById("crop-name").value;
    const quantity = parseInt(document.getElementById("stored-quantity").value);
    const capacity = parseInt(document.getElementById("max-capacity").value);

    if (editId) {
        const storage = storageData.find((item) => item.id === editId);
        storage.crop = cropName;
        storage.quantity = quantity;
        storage.capacity = capacity;
        storage.lastUpdated = new Date().toISOString().split("T")[0];
    } else {
        storageData.push({
            id: storageData.length + 1,
            crop: cropName,
            quantity,
            capacity,
            lastUpdated: new Date().toISOString().split("T")[0],
        });
    }

    closeModal();
    renderTable();
});

function deleteStorage(id) {
    const index = storageData.findIndex((item) => item.id === id);
    storageData.splice(index, 1);
    renderTable();
}

// Initialize
document.addEventListener("DOMContentLoaded", () => {
    renderTable();
    updateChart();
});
