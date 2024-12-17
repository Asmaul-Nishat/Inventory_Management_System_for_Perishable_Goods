let sensorData = [];
let editingIndex = null; // Track the editing index

// Function to render the sensor data table
function renderSensorTable() {
    const sensorTableBody = document.getElementById('sensor-table-body');
    sensorTableBody.innerHTML = '';

    sensorData.forEach((sensor, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${sensor.sensor_ID}</td>
            <td>${sensor.timestamp}</td>
            <td>${sensor.temperature}</td>
            <td>${sensor.humidity}</td>
            <td>${sensor.oxidationLevel}</td>
            <td>${sensor.co2Level}</td>
            <td>${sensor.movementSensorData}</td>
            <td>
                <button onclick="editSensorData(${index})">Edit</button>
                <button onclick="deleteSensorData(${index})">Delete</button>
            </td>
        `;
        sensorTableBody.appendChild(tr);
    });

    document.getElementById('total-sensor-data').innerText = sensorData.length;
}

// Function to save or update sensor data
function saveSensorData() {
    const sensor_ID = document.getElementById('modal-sensor-id').value;
    const timestamp = document.getElementById('modal-timestamp').value;
    const temperature = parseFloat(document.getElementById('modal-temperature').value);
    const humidity = parseFloat(document.getElementById('modal-humidity').value);
    const oxidationLevel = parseFloat(document.getElementById('modal-oxidation').value);
    const co2Level = parseFloat(document.getElementById('modal-co2').value);
    const movementSensorData = document.getElementById('modal-movement').value;

    if (editingIndex !== null) {
        // Update existing sensor data
        sensorData[editingIndex] = {
            sensor_ID,
            timestamp,
            temperature,
            humidity,
            oxidationLevel,
            co2Level,
            movementSensorData,
        };
        editingIndex = null; // Reset editing index
    } else {
        // Add new sensor data
        const newSensor = {
            sensor_ID,
            timestamp,
            temperature,
            humidity,
            oxidationLevel,
            co2Level,
            movementSensorData,
        };
        sensorData.push(newSensor);
    }

    closeModal();
    renderSensorTable();
}

// Function to edit sensor data
function editSensorData(index) {
    const sensor = sensorData[index];
    document.getElementById('modal-sensor-id').value = sensor.sensor_ID;
    document.getElementById('modal-timestamp').value = sensor.timestamp;
    document.getElementById('modal-temperature').value = sensor.temperature;
    document.getElementById('modal-humidity').value = sensor.humidity;
    document.getElementById('modal-oxidation').value = sensor.oxidationLevel;
    document.getElementById('modal-co2').value = sensor.co2Level;
    document.getElementById('modal-movement').value = sensor.movementSensorData;

    editingIndex = index; // Set the editing index
    showSensorDataModal();
}

// Function to delete sensor data
function deleteSensorData(index) {
    sensorData.splice(index, 1);
    renderSensorTable();
}

// Modal functions
function closeModal() {
    document.getElementById('sensorDataModal').style.display = 'none';
    resetModal();
}

function showSensorDataModal() {
    document.getElementById('sensorDataModal').style.display = 'block';
}

function resetModal() {
    document.getElementById('modal-sensor-id').value = '';
    document.getElementById('modal-timestamp').value = '';
    document.getElementById('modal-temperature').value = '';
    document.getElementById('modal-humidity').value = '';
    document.getElementById('modal-oxidation').value = '';
    document.getElementById('modal-co2').value = '';
    document.getElementById('modal-movement').value = '';
    editingIndex = null; // Reset editing index
}

// Initialize the table on load
window.onload = renderSensorTable;
