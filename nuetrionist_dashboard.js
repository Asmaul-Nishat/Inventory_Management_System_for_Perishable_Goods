document.getElementById("product-analysis-form").addEventListener("submit", function (event) {
    event.preventDefault();

    const productName = document.getElementById("product-name").value;
    const productCode = document.getElementById("product-code").value;
    const tempLow = document.getElementById("temp-low").value;
    const tempHigh = document.getElementById("temp-high").value;
    const humidityLow = document.getElementById("humidity-low").value;
    const humidityHigh = document.getElementById("humidity-high").value;
    const quality = document.getElementById("quality").value;

    // Display Data for Testing
    console.log("Product Name:", productName);
    console.log("Product Code:", productCode);
    console.log("Temperature Low:", tempLow);
    console.log("Temperature High:", tempHigh);
    console.log("Humidity Low:", humidityLow);
    console.log("Humidity High:", humidityHigh);
    console.log("Quality:", quality);

    alert("Product Analysis Submitted Successfully!");
    document.getElementById("product-analysis-form").reset();
});
