window.onload = function() {
    // Cerrar el menú desplegable al cargar la página
    var dropdownContent = document.getElementById("myDropdown");
    dropdownContent.style.display = "none";
};
function toggleDropdown() {
    var dropdown = document.getElementById("myDropdown");
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
    }
}
