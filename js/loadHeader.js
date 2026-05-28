fetch("/Photo-Impact/partials/header.html")
  .then(response => response.text())
  .then(data => {

    document.getElementById("header-container").innerHTML = data;

    // Check if user is logged in
    const user = JSON.parse(localStorage.getItem("loggedInUser"));

    const loginNav = document.querySelector(".nav-login");
    const signUpNav = document.getElementById("nav-signUp");

    // ADMIN LINKS
    const quotesNav = document.getElementById("quotes");
    const bookingsNav = document.getElementById("bookings");

    // Hide admin links by default
    if (quotesNav) {
      quotesNav.style.display = "none";
    }

    if (bookingsNav) {
      bookingsNav.style.display = "none";
    }

    // If logged in
    if (user && loginNav) {

      // Hide Sign up button
      if (signUpNav) {
        signUpNav.style.display = "none";
      }

      // Change Login into Logout
      loginNav.textContent = "Log out";

      // Redirect to PHP logout
      loginNav.href = "/Photo-Impact/logout.php";

      // SHOW ADMIN LINKS ONLY
      if (user.role === "admin") {

        if (quotesNav) {
          quotesNav.style.display = "block";
        }

        if (bookingsNav) {
          bookingsNav.style.display = "block";
        }

      }

    }

    // Highlight active page
    const currentPath = window.location.pathname.split("/").pop();

    document.querySelectorAll(".topnav a").forEach(link => {

      const linkPath = link.getAttribute("href")?.split("/").pop();

      if (linkPath === currentPath) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }

    });

  });