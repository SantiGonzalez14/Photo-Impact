fetch("/MAC272/Project/partials/footer.html")
  .then(response => response.text())
  .then(data => {
    document.getElementById("footer-container").innerHTML = data;
  });