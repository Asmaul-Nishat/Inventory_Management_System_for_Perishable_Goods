// Mock Data
const employees = [
    { id: 1, name: "John Doe", role: "Farm Manager", contact: "123-456-7890" },
    { id: 2, name: "Jane Smith", role: "Worker", contact: "987-654-3210" },
];

const reportsGenerated = 10;

// Render Employee Table
function renderEmployeeTable() {
    const tableBody = document.getElementById("employee-table-body");
    tableBody.innerHTML = "";

    employees.forEach((employee) => {
        const row = `
            <tr>
                <td>${employee.id}</td>
                <td>${employee.name}</td>
                <td>${employee.role}</td>
                <td>${employee.contact}</td>
                <td>
                    <button class="btn" onclick="editEmployee(${employee.id})">Edit</button>
                    <button class="btn" onclick="deleteEmployee(${employee.id})">Delete</button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// Add Employee
function addEmployee() {
    const name = prompt("Enter employee name:");
    const role = prompt("Enter employee role:");
    const contact = prompt("Enter employee contact:");

    if (name && role && contact) {
        const newEmployee = {
            id: employees.length + 1,
            name,
            role,
            contact,
        };
        employees.push(newEmployee);
        renderEmployeeTable();
        alert("Employee added successfully!");
    }
}

// Edit Employee
function editEmployee(id) {
    const employee = employees.find((emp) => emp.id === id);
    if (!employee) return alert("Employee not found!");

    const newName = prompt("Edit employee name:", employee.name);
    const newRole = prompt("Edit employee role:", employee.role);
    const newContact = prompt("Edit employee contact:", employee.contact);

    if (newName) employee.name = newName;
    if (newRole) employee.role = newRole;
    if (newContact) employee.contact = newContact;

    renderEmployeeTable();
    alert("Employee updated successfully!");
}

// Delete Employee
function deleteEmployee(id) {
    const index = employees.findIndex((emp) => emp.id === id);
    if (index !== -1) {
        employees.splice(index, 1);
        renderEmployeeTable();
        alert("Employee deleted successfully!");
    }
}

// Chart for Reports
function renderReportsChart() {
    const ctx = document.getElementById("reportsChart").getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Reports Generated"],
            datasets: [
                {
                    label: "Reports",
                    data: [reportsGenerated],
                    backgroundColor: ["#3498db"],
                },
            ],
        },
    });
}

// Reset Data
function resetData() {
    if (confirm("Are you sure you want to reset all data?")) {
        employees.length = 0;
        renderEmployeeTable();
        alert("All data has been reset!");
    }
}

// Redirect to Inventory Page
function redirectToInventory() {
    window.location.href = "inventory-management.html"; // Adjust this URL to match your project's structure
}

// Initialize
document.addEventListener("DOMContentLoaded", () => {
    renderEmployeeTable();
    renderReportsChart();
});
