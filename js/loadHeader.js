fetch("../partials/header.html")
  .then(response => response.text())
  .then(data => {
    document.getElementById("header-container").innerHTML = data;

    // Handle login/logout state
    const user = JSON.parse(localStorage.getItem("loggedInUser"));
    const loginNav = document.querySelector(".nav-login");
    const signUpNav = document.getElementById("nav-signUp");

    if (user && loginNav) {
      signUpNav.style.display = "none";
      loginNav.textContent = "Logout";
      loginNav.href = "#";
      loginNav.addEventListener("click", function (e) {
        e.preventDefault();
        localStorage.removeItem("loggedInUser");
        alert("You have been logged out.");
        window.location.href = "login.html";
      });
    }

    // Highlight active nav link
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