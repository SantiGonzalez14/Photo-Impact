fetch("../partials/header.php")
  .then(response => response.text())
  .then(data => {

    document.getElementById("header-container").innerHTML = data;

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