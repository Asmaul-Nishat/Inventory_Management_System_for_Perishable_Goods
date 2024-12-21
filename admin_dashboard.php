<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo4.png" alt="Farm Logo">
            <h1>Admin Dashboard</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#overview">Overview</a></li>
                <!-- <li><a href="#user-management">Users</a></li> -->
                <li><a href="#inventory-management">Inventory</a></li>
                <li><a href="#reports">Reports</a></li>
                <!-- <li><a href="#settings">Settings</a></li> -->
                <li><a href="mainPage.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Overview Section -->
        <!-- <section id="overview">
            <h2>Overview</h2>
            <div class="stats">
                <div class="stat-item">
                    <h3>50</h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-item">
                    <h3>120</h3>
                    <p>Inventory Items</p>
                </div>
                <div class="stat-item">
                    <h3>20</h3>
                    <p>Pending Orders</p>
                </div>
            </div> -->
        </section>

        <!-- User Management Section -->
        <!-- <section id="user-management">
            <h2>User Management</h2>
            <p>Manage all roles and users.</p>
            <button onclick="location.href='agroOfficer_dashboard.html'">Agro Officers</button>
            <button onclick="location.href='distributor.html'">Distributors</button>
            <button onclick="location.href='nuetrionist_dashboad.html'">Nuetrionist</button>
            <button onclick="location.href='warehouse_manager_Dashboard.html'">Storage Manager</button>
            
        </section> -->

        <!-- Inventory Management Section -->
        <section id="inventory-management">
            <h2>Inventory Management</h2>
            <p>Manage and track all inventory items.</p>
            <button onclick="location.href='farmer.php'">Farm</button>
            <button onclick="location.href='storage.php'">Storage Details</button>
            <button onclick="location.href='transportation.php'">Transportation</button>
            <button onclick="location.href='consumer_dashboard.php'">consumer_dashboard</button>
            <button onclick="location.href='product.php'">product</button>
            <button onclick="location.href='store_dashboard.php'">Store</button>
            <button onclick="location.href='shipment_dashboard.php'">Shipment</button>
            <button onclick="location.href='distributor_dashboard.php'">Distributor</button>
        </section>

        <!-- Reports Section -->
        <section id="reports">
            <h2>Reports</h2>
            <p>Access reports across the system.</p>
            <button onclick="location.href='harvest_dashboard.php'">Harvest</button>
            <button onclick="location.href='customer_sales_record_dashboard.php'">sales record</button>
            
            <button onclick="location.href='transportation_sensor_data.php'">transportation_sensor_data</button>
            <button onclick="location.href='storage_sensor_data.php'">storage_sensor_data</button>
            <button onclick="location.href='purchases_order_dashboard.php'">Purchases Order Reports</button>
        </section>

        <!-- Settings Section -->
        <!-- <section id="settings">
            <h2>Settings</h2>
            <button onclick="location.href='admin_settings.html'">Settings</button>
            <p>Configure system preferences.</p>
        </section> -->
    </main>
    <footer>
        <p>&copy; 2024 Farm Inventory Management. All rights reserved.</p>
    </footer>
</body>
</html>
