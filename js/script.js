// ELEMENTS
const noOfPicsSelect = document.getElementById("no-of-pics");
const extraPicsSection = document.getElementById("extra-pics-section");
const lblNoOfPics = document.getElementById("lbl-no-of-pics");

const digitalPictures = document.getElementById("digital-pictures");
const physicalPictures = document.getElementById("physical-pictures");


// SHOW/HIDE EXTRA PICTURES FIELD
noOfPicsSelect.addEventListener("change", function () {

    const showExtra = this.value === "100_plus";

    extraPicsSection.style.display = showExtra
        ? "block"
        : "none";
});


// DIGITAL PICTURES SELECTED
digitalPictures.addEventListener("change", function () {

    lblNoOfPics.textContent = "Hours to be covered";

    noOfPicsSelect.innerHTML = `
        <option value="2">2 hours</option>
        <option value="4">3-4 hours</option>
        <option value="6">5-6 hours</option>
    `;

    extraPicsSection.style.display = "none";
});


// PHYSICAL PICTURES SELECTED
physicalPictures.addEventListener("change", function () {

    lblNoOfPics.textContent = "Number of pictures";

    noOfPicsSelect.innerHTML = `
        <option value="50">50</option>
        <option value="70">70</option>
        <option value="100">100</option>
        <option value="+100">+100</option>
    `;
});


// OPTIONAL FORM VALIDATION BEFORE SUBMIT
document.getElementById("quote").addEventListener("submit", function (event) {

    const isPhysical = physicalPictures.checked;
    const selectedValue = noOfPicsSelect.value;

    // VALIDATE EXTRA PICTURES
    if (isPhysical && selectedValue === "+100") {

        const extraPicsInput = document.getElementById("extra-pics");
        const totalPictures = parseInt(extraPicsInput.value);

        if (isNaN(totalPictures) || totalPictures <= 100) {

            event.preventDefault();

            alert("Please enter a valid number greater than 100.");
        }
    }
});