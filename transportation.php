<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transportation Dashboard</title>
  <link rel="stylesheet" href="transportation.css">
</head>
<body>
  <!-- Wrapper -->
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Transportation Menu</h2>
      <button onclick="showTransportationModal()" class="nav-button">Add Vehicle</button>
      <button onclick="logout()" class="nav-button">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <header>
        <h1>Transportation Dashboard</h1>
      </header>

      <!-- Stats -->
      <div class="stats-container">
        <div class="stat-card">
          <h3>Total Vehicles</h3>
          <p id="total-vehicles">0</p>
        </div>
      </div>

      <!-- Table -->
      <div class="table-container">
        <h2>Vehicle Data</h2>
        <input type="text" id="vehicle-search" placeholder="Search vehicles..." onkeyup="filterVehicleTable()" class="search-bar">
        <table>
          <thead>
            <tr>
              <th>Vehicle Registration Number</th>
              <th>Manufacturing Year</th>
              <th>Mileage</th>
              <th>Last Servicing Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="vehicle-table-body"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal" id="transportationModal">
    <h3>Add/Edit Vehicle</h3>
    <label>Vehicle Registration Number:</label>
    <input type="text" id="modal-vehicle-reg" />
    <label>Manufacturing Year:</label>
    <input type="number" id="modal-manufacturing-year" />
    <label>Mileage (km):</label>
    <input type="number" id="modal-mileage" />
    <label>Last Servicing Date:</label>
    <input type="date" id="modal-servicing-date" />
    <button onclick="saveVehicle()">Save</button>
    <button onclick="closeModal()">Cancel</button>
  </div>

  <script src="transportation.js"></script>
</body>
</html>
