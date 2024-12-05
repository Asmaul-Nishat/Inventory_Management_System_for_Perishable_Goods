// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", () => {
    // Get the form, dropdown, and input fields
    const loginForm = document.querySelector(".login_form");
    const employeeSelect = document.getElementById("Employee");
    const loginId = document.getElementById("login_id");
    const loginPassword = document.getElementById("login_password");

    // Add an event listener for the form submission
    loginForm.addEventListener("submit", (event) => {
        // Prevent default form submission behavior
        event.preventDefault();

        // Get selected employee role, ID, and password
        const selectedRole = employeeSelect.value;
        const id = loginId.value.trim();
        const password = loginPassword.value.trim();

        // Validation checks (example)
        if (id === "" || password === "") {
            alert("Please fill in all fields.");
            return;
        }

        // Simulate login based on selected role
        switch (selectedRole) {
            case "Admin":
                window.location.href = "/admin_dashboard.html"; // Redirect to admin dashboard
                break;
            case "Agro Officer":
                window.location.href = "/agroOfficer_dashboard.html"; // Redirect to Agro Officer page
                break;
            case "Neutrionist":
                window.location.href = "/Neutrionist.html"; // Redirect to Nutritionist page
                break;
            case "WareHouse_Manager":
                window.location.href = "/warehouse_manager_Dashboard.html"; // Redirect to Warehouse Manager page
                break;
            case "Distributor":
                window.location.href = "/distributor_dashboard.html"; // Redirect to Distributor page
                break;
            case "Farmer":
                window.location.href = "/farmer.html"; // Redirect to Farmer page
                break;
            case "Consumer":
                window.location.href = "/admin_dashboard.html"; // Redirect to Consumer page
                break;
            default:
                alert("Invalid role selected!");
        }
    });
});


// new part for graph

// Monthly Labels for the Graph
const monthlyLabels = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

// Yearly Data for Five Graphs
const yearlyData = {
    Graph1: [100, 120, 130, 140, 150, 160, 170, 180, 190, 200, 210, 220],
    Graph2: [200, 190, 180, 170, 160, 150, 140, 130, 120, 110, 100, 90],
    Graph3: [50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, 105],
    Graph4: [300, 310, 320, 330, 340, 350, 360, 370, 380, 390, 400, 410],
    Graph5: [120, 130, 140, 150, 160, 170, 180, 190, 200, 210, 220, 230]
};

// Wait for DOM to load
document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById("yearlyDataGraph");
    if (!canvas) {
        console.error("Canvas with id 'yearlyDataGraph' not found.");
        return;
    }

    const ctx = canvas.getContext("2d");
    new Chart(ctx, {
        type: "line", // Chart type: line
        data: {
            labels: monthlyLabels,
            datasets: [
                {
                    label: "Available Stcok",
                    data: yearlyData.Graph1,
                    borderColor: "rgba(75, 192, 192, 1)",
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    fill: true
                },
                {
                    label: "Sold Crops",
                    data: yearlyData.Graph2,
                    borderColor: "rgba(255, 99, 132, 1)",
                    backgroundColor: "rgba(255, 99, 132, 0.2)",
                    fill: true
                },
                {
                    label: "Total Order",
                    data: yearlyData.Graph3,
                    borderColor: "rgba(54, 162, 235, 1)",
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    fill: true
                },
                {
                    label: "Order Complete",
                    data: yearlyData.Graph4,
                    borderColor: "rgba(255, 206, 86, 1)",
                    backgroundColor: "rgba(255, 206, 86, 0.2)",
                    fill: true
                },
                {
                    label: "Order Processing",
                    data: yearlyData.Graph5,
                    borderColor: "rgba(153, 102, 255, 1)",
                    backgroundColor: "rgba(153, 102, 255, 0.2)",
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: "Yearly Data for Available Stock, Sold Crops, Total Order, Order Completed, Order Processing",
                    color: "white", // Title text color
                font: {
                    size: 25 // Optional: Adjust font size
                }
                },
                legend: {
                    position: "top",
                    labels:{
                        color:"white"
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: "Months",
                        color: "white",  // Color of the y-axis title
                        font: {
                            size: 18
                        }
                        
                    },
                    ticks:{
                        color:"white"
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: "Values",
                        color:"white",
                        font:{
                            size:18
                        }
                    },
                    beginAtZero: true,
                    ticks:{
                        color:"white"
                    }
                }
            }
        }
    });
});
