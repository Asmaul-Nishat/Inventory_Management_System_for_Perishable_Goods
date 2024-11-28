// Sample Data
const reportData = [
    { id: 1, crop: "Wheat", quantity: 500, capacity: 1000, lastUpdated: "2024-11-27" },
    { id: 2, crop: "Rice", quantity: 300, capacity: 700, lastUpdated: "2024-11-28" },
    { id: 3, crop: "Corn", quantity: 200, capacity: 500, lastUpdated: "2024-11-26" },
];

// Render Reports Table
function renderReportsTable(filteredData = reportData) {
    const tableBody = document.getElementById("reports-table-body");
    tableBody.innerHTML = "";

    filteredData.forEach((item) => {
        const row = `
            <tr>
                <td>${item.id}</td>
                <td>${item.crop}</td>
                <td>${item.quantity}</td>
                <td>${item.capacity}</td>
                <td>${item.lastUpdated}</td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// Update Chart
function updateChart(filteredData = reportData) {
    const cropNames = [...new Set(filteredData.map((item) => item.crop))];
    const cropQuantities = cropNames.map(
        (crop) => filteredData.filter((item) => item.crop === crop)
            .reduce((sum, item) => sum + item.quantity, 0)
    );

    const ctx = document.getElementById("reportsChart").getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: cropNames,
            datasets: [{
                label: "Stored Quantity (kg)",
                data: cropQuantities,
                backgroundColor: ["#007bff", "#28a745", "#ffc107"],
            }],
        },
        options: { responsive: false },
    });
}

// Apply Filters
document.getElementById("filter-form").addEventListener("submit", (event) => {
    event.preventDefault();

    const cropFilter = document.getElementById("crop-filter").value;
    const dateFilter = document.getElementById("date-filter").value;

    let filteredData = reportData;

    if (cropFilter !== "all") {
        filteredData = filteredData.filter((item) => item.crop === cropFilter);
    }

    if (dateFilter) {
        filteredData = filteredData.filter((item) => item.lastUpdated === dateFilter);
    }

    renderReportsTable(filteredData);
    updateChart(filteredData);
});

// Initialize
document.addEventListener("DOMContentLoaded", () => {
    renderReportsTable();
    updateChart();
});
