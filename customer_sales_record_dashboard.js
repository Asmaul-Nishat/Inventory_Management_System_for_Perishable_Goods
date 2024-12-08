let salesRecords = [];

// Handle form submission
document.getElementById('sales-form').addEventListener('submit', (e) => {
    e.preventDefault();

    const invoiceID = document.getElementById('invoice-id').value.trim();
    const productID = document.getElementById('product-id').value.trim();
    const quantity = parseInt(document.getElementById('quantity').value);
    const unitPrice = parseFloat(document.getElementById('unit-price').value);
    const salesStatus = document.getElementById('sales-status').value;
    const purchaseLocation = document.getElementById('purchase-location').value;
    const date = document.getElementById('date').value;
    const storageID = document.getElementById('storage-id').value.trim();
    const storeID = document.getElementById('store-id').value.trim();

    const totalPrice = quantity * unitPrice;

    const record = {
        invoiceID,
        productID,
        quantity,
        unitPrice,
        totalPrice,
        salesStatus,
        purchaseLocation,
        id: purchaseLocation === "Storage" ? storageID : storeID,
        date,
    };

    salesRecords.unshift(record); // Add at the top
    updateRecordTable();
});

// Dynamically render the table
function updateRecordTable() {
    const tableBody = document.getElementById('records-body');
    tableBody.innerHTML = "";

    salesRecords.forEach((record) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${record.invoiceID}</td>
            <td>${record.productID}</td>
            <td>${record.quantity}</td>
            <td>${record.unitPrice.toFixed(2)}</td>
            <td>${record.totalPrice.toFixed(2)}</td>
            <td>${record.salesStatus}</td>
            <td>${record.purchaseLocation}</td>
            <td>${record.id}</td>
            <td>${record.date}</td>
        `;
        tableBody.appendChild(row);
    });
}

// Toggle visibility for dynamic fields
function toggleLocationFields() {
    const purchaseLocation = document.getElementById('purchase-location').value;
    if (purchaseLocation === "Storage") {
        document.getElementById('storage-id').style.display = "block";
        document.getElementById('store-id').style.display = "none";
    } else if (purchaseLocation === "Store") {
        document.getElementById('store-id').style.display = "block";
        document.getElementById('storage-id').style.display = "none";
    } else {
        document.getElementById('storage-id').style.display = "none";
        document.getElementById('store-id').style.display = "none";
    }
}

// Navigate back to the consumer dashboard
// function goToDashboard() {
//     window.location.href = 'consumer_dashboard.html';
// }

// Logout simulation
function logout() {
    alert('You have logged out.');
    window.location.href = 'mainPage.html';
}
// Handle navigation back to the correct dashboard
function goToDashboard() {
    const urlParams = new URLSearchParams(window.location.search);
    const source = urlParams.get('source'); // Get the source query parameter

    if (source === "storage") {
        window.location.href = 'storage_dashboard.html';
    } else if (source === "store") {
        window.location.href = 'store_dashboard.html';
    } else {
        alert("Unknown source! Redirecting to the main dashboard.");
        window.location.href = 'dashboard.html'; // Fallback to the general main dashboard
    }
}
