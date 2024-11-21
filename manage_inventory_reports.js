// Open the modal to add a new row
function addNewRow() {
    document.getElementById("add-report-modal").style.display = "flex";
}

// Close the modal when the user clicks the close button
function closeModal() {
    document.getElementById("add-report-modal").style.display = "none";
}

// Handle form submission and add a new row to the table
document.getElementById("add-report-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent page reload on form submission

    // Get form values
    const productName = document.getElementById("product-name").value;
    const productId = document.getElementById("product-id").value;
    const quantity = document.getElementById("quantity").value;
    const humRange = document.getElementById("hum-range").value;
    const tempRange = document.getElementById("temp-range").value;
    const location = document.getElementById("location").value;
    const lastUpdate=document.getElementById("last-update").value;

    // Create a new row for the inventory table
    const newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td>${productName}</td>
        <td>${productId}</td>
        <td>${quantity}</td>
        <td>${tempRange}</td>
        <td>${humRange}</td>
        <td>${location}</td>
        <td>${lastUpdate}
        <td><button class="btn" onclick="editRow(this)">Edit</button> <button class="btn" onclick="deleteRow(this)">Delete</button></td>
    `;

    // Append the new row to the table
    document.getElementById("inventory-table-body").appendChild(newRow);

    // Close the modal after adding the row
    closeModal();

    // Reset form fields after submission
    document.getElementById("add-report-form").reset();
});

// Edit an existing row
function editRow(button) {
    const row = button.parentNode.parentNode;
    const cells = row.getElementsByTagName("td");

    // Set the values of the cells into the form inputs (for editing)
    document.getElementById("product-name").value = cells[0].innerText;
    document.getElementById("product-id").value = cells[1].innerText;
    document.getElementById("quantity").value = cells[2].innerText;
    document.getElementById("temp-range").value = cells[3].innerText;
    document.getElementById("hum-range").value = cells[4].innerText;
    document.getElementById("location").value = cells[5].innerText;
    document.getElementById("last-update").value = cells[6].innerText;

    // Remove the row after editing (to replace it with the updated values)
    deleteRow(button);
}

// Delete a row
function deleteRow(button) {
    const row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}
