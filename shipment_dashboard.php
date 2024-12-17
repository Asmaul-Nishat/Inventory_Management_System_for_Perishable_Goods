<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipment Dashboard</title>
  <link rel="stylesheet" href="shipment_dashboard.css">
</head>

<body>
  <!-- Wrapper -->
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Shipment Menu</h2>
      <button onclick="showShipmentModal()" class="nav-button">Add Shipment</button>
      <button onclick="logout()" class="nav-button">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <header>
        <h1>Shipment Dashboard</h1>
      </header>

      <div class="stats-container">
        <div class="stat-card">
          <h3>Total Shipments</h3>
          <p id="total-shipments">0</p>
        </div>
      </div>

      <div class="table-container">
        <h2>Shipment Data</h2>
        <input
          type="text"
          id="shipment-search"
          placeholder="Search shipment records..."
          onkeyup="filterShipmentTable()"
          class="search-bar"
        />
        <table>
          <thead>
            <tr>
              <th>Shipment ID</th>
              <th>Shipment Date</th>
              <th>Expected Delivery Date</th>
              <th>Shipment Product Quantity</th>
              <th>Product Loading (Date, Time)</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="shipment-table-body"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal for Shipment -->
  <div class="modal" id="shipmentModal">
    <h3>Add/Edit Shipment</h3>
    <label>Shipment ID:</label>
    <input type="text" id="modal-shipment-id">
    <label>Shipment Date:</label>
    <input type="date" id="modal-shipment-date">
    <label>Expected Delivery Date:</label>
    <input type="date" id="modal-delivery-date">
    <label>Shipment Product Quantity:</label>
    <input type="number" id="modal-shipment-quantity">
    <label>Product Loading Date:</label>
    <input type="date" id="modal-loading-date">
    <label>Product Loading Time:</label>
    <input type="time" id="modal-loading-time">
    <button onclick="saveShipment()">Save</button>
    <button onclick="closeModal()">Cancel</button>
  </div>

  <script src="shipment_dashboard.js"></script>
</body>

</html>
