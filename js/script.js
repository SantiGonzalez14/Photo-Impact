
// +100 picture toggle logic
document.getElementById("no-of-pics").addEventListener("change", function () {
    const showExtra = this.value === "+100";
    document.getElementById("extra-pics-section").style.display = showExtra ? "block" : "none";
});

// DIGITAL PICTURES SELECTED
document.getElementById("digital-pictures").addEventListener("change", function () {
    document.getElementById("lbl-no-of-pics").textContent = "Hours to be covered";
    const select = document.getElementById("no-of-pics");

    select.innerHTML = `
        <option value="2">2 hours</option>
        <option value="4">3-4 hours</option>
        <option value="6">5-6 hours</option>
    `;
    document.getElementById("extra-pics-section").style.display = "none";
});

// PHYSICAL PICTURES SELECTED
document.getElementById("physical-pictures").addEventListener("change", function () {
    document.getElementById("lbl-no-of-pics").textContent = "Number of pictures";
    const select = document.getElementById("no-of-pics");

    select.innerHTML = `
        <option value="50">50</option>
        <option value="70">70</option>
        <option value="100">100</option>
        <option value="+100">+100</option>
    `;
});

// GENERATE QUOTE
function generateQuote() {
    const eventType = document.getElementById("type-of-event").value;
    const isPhysical = document.getElementById("physical-pictures").checked;
    const picType = isPhysical ? "Physical Album" : "Digital Pictures";
    const numPics = document.getElementById("no-of-pics").value;

    let price = 0;

    if (isPhysical) {
        if (numPics === "50") price = 500;
        if (numPics === "70") price = 750;
        if (numPics === "100") price = 900;
        if (numPics === "+100") {
            const extraTotal = parseInt(document.getElementById("extra-pics").value);
            if (!isNaN(extraTotal) && extraTotal > 100) {
                const extraPics = extraTotal - 100;
                price = 900 + extraPics * 10;
            } else {
                alert("Please enter a valid number of pictures above 100.");
                return;
            }
        }
    } else {
        if (numPics === "2") price = 300;
        if (numPics === "4") price = 600;
        if (numPics === "6") price = 750;
    }

    //Showing the result container and hiding the quote container 
    document.getElementById("containerResult").style.display = "block";
    document.getElementById("containerQuote").style.display = "none"; 

    //Setting values of the elements inside container result
    document.getElementById("p-event-selected").textContent = "Type of event: " + eventType;
    document.getElementById("p-type-of-pics").textContent = "Photo format: " + picType;
    document.getElementById("p-number-of-pics").textContent = 
    isPhysical 
        ? (numPics === "+100"
            ? "Number of pictures: " + document.getElementById("extra-pics").value
            : "Number of pictures: " + numPics)
        : "Hours to be covered: " + numPics;
    document.getElementById("p-quote-price").textContent = `The coverage of the event is: $${price}`;
}

// HIDE QUOTE
function hideQuote() {
    document.getElementById("containerResult").style.display = "none";
    document.getElementById("containerQuote").style.display = "block";
}

function sendQuote() {
    alert("Your quote has been saved, you will be contacted soon to confirm date and location of event.");
}
