// Mock Data (Replace with a database fetch in a real application)
const productsData = [
    {
        productName: "Milk",
        productCode: "M001",
        tempLow: "1°C",
        tempHigh: "4°C",
        humidityLow: "40%",
        humidityHigh: "60%",
        quality: "High Quality",
        price: 3.50,
        image: "milk.jpg"
    },
    {
        productName: "Cheese",
        productCode: "C002",
        tempLow: "2°C",
        tempHigh: "8°C",
        humidityLow: "30%",
        humidityHigh: "50%",
        quality: "Medium Quality",
        price: 5.99,
        image: "pic1.jpg"
    },
    {
        productName: "Yogurt",
        productCode: "Y003",
        tempLow: "2°C",
        tempHigh: "5°C",
        humidityLow: "50%",
        humidityHigh: "70%",
        quality: "High Quality",
        price: 4.00,
        image: "product1.jpg"
    },
    {
        productName: "Butter",
        productCode: "B004",
        tempLow: "4°C",
        tempHigh: "10°C",
        humidityLow: "35%",
        humidityHigh: "55%",
        quality: "Low Quality",
        price: 2.50,
        image: "butter.jpg"
    }
];

// Display Products Function
function displayProducts(products) {
    const productList = document.getElementById('products-list');
    productList.innerHTML = ''; // Clear the current products

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');
        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.productName}">
            <div class="product-name">${product.productName}</div>
            <div class="product-price">$${product.price.toFixed(2)}</div>
            <div class="product-details">
                Product Code: ${product.productCode}<br>
                Temperature: ${product.tempLow} - ${product.tempHigh}<br>
                Humidity: ${product.humidityLow} - ${product.humidityHigh}<br>
                Quality: ${product.quality}
            </div>
            <button class="order-btn" onclick="openPopup('${product.productCode}')">Order Now</button>
        `;
        productList.appendChild(productCard);
    });
}

// Custom Filter Logic for Price and Quality
function filterProducts() {
    const searchValue = document.getElementById('search-input').value.toLowerCase();
    const qualityValue = document.getElementById('filter-quality').value;
    const priceValue = document.getElementById('filter-price').value;

    const filteredProducts = productsData.filter(product => {
        const matchesSearch = product.productName.toLowerCase().includes(searchValue);
        const matchesQuality = qualityValue ? product.quality === qualityValue : true;
        const matchesPrice = priceValue === "Low" ? product.price < 5 : priceValue === "High" ? product.price >= 5 : true;
        return matchesSearch && matchesQuality && matchesPrice;
    });

    displayProducts(filteredProducts);
}

// Open Order Popup Form
function openPopup(productCode) {
    document.getElementById('order-popup').style.display = "block";
    document.getElementById('order-form').dataset.productCode = productCode; // Store productCode for form submission
}

// Close Order Popup Form
function closePopup() {
    document.getElementById('order-popup').style.display = "none";
}

// Handle Order Form Submission
document.getElementById('order-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent page reload on form submission

    const productCode = event.target.dataset.productCode;
    const quantity = document.getElementById('quantity').value;
    const location = document.getElementById('location').value;
    const phone = document.getElementById('phone').value;
    const name = document.getElementById('name').value;

    // Submit order (You can store this in a database or cart system)
    alert(`Order for Product Code: ${productCode}\nQuantity: ${quantity}\nLocation: ${location}\nPhone: ${phone}\nName: ${name} has been submitted.`);

    // Close popup after submission
    closePopup();
});

// Initially display all products
displayProducts(productsData);

// Close Order Popup Form
function closePopup() {
    document.getElementById('order-popup').style.display = "none";
}

// Mock data for previous orders (replace with actual data from a database later)
const orderHistory = [
    { productName: "Product A", quantity: 2, location: "New York", phone: "1234567890", status: "Completed" },
    { productName: "Product B", quantity: 1, location: "California", phone: "9876543210", status: "Pending" },
    { productName: "Product C", quantity: 3, location: "Texas", phone: "1112223333", status: "Completed" }
];

// Function to populate the order history table
function showOrderHistory() {
    const orderHistoryList = document.getElementById('order-history-list');

    // Clear existing data in the table
    orderHistoryList.innerHTML = "";

    // Loop through the order history array and add rows to the table
    orderHistory.forEach(order => {
        const row = document.createElement('tr');

        // Add product name
        const productCell = document.createElement('td');
        productCell.textContent = order.productName;
        row.appendChild(productCell);

        // Add quantity
        const quantityCell = document.createElement('td');
        quantityCell.textContent = order.quantity;
        row.appendChild(quantityCell);

        // Add transport location
        const locationCell = document.createElement('td');
        locationCell.textContent = order.location;
        row.appendChild(locationCell);

        // Add phone number
        const phoneCell = document.createElement('td');
        phoneCell.textContent = order.phone;
        row.appendChild(phoneCell);

        // Add order status
        const statusCell = document.createElement('td');
        statusCell.textContent = order.status;
        row.appendChild(statusCell);

        // Append the row to the table
        orderHistoryList.appendChild(row);
    });

    // Show the order history section
    document.querySelector('.order-history').style.display = "block";
}

// Call this function when the "View Order History" button is clicked
document.querySelector('button').addEventListener('click', showOrderHistory);

