// Load orders from localStorage
function loadOrderReports() {
    const orders = JSON.parse(localStorage.getItem("orders")) || [];
    const reportsBody = document.getElementById("reports-body");
    reportsBody.innerHTML = ""; // Clear previous entries

    if (orders.length === 0) {
        const noDataRow = document.createElement("tr");
        noDataRow.innerHTML = `<td colspan="10">No orders found.</td>`;
        reportsBody.appendChild(noDataRow);
        return;
    }

    // Add each order to the table
    orders.forEach(order => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${order.orderID}</td>
            <td>${order.productName}</td>
            <td>${order.productType}</td>
            <td>${order.unitPrice.toFixed(2)}</td>
            <td>${order.quantity}</td>
            <td>${order.totalPrice}</td>
            <td>${order.location}</td>
            <td>${order.phone}</td>
            <td>${order.name}</td>
            <td>${order.orderDate}</td>
        `;
        reportsBody.appendChild(row);
    });
}

// Load the order reports when the page loads
document.addEventListener("DOMContentLoaded", loadOrderReports);
