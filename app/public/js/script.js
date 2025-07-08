// Toggle menu for burger icon
document.addEventListener("DOMContentLoaded", function () {
    // ======= BURGER =======
    document.getElementById("burgerMenu").addEventListener("click", function() {
        document.getElementById("navModal").classList.add("active");
    });

    document.querySelector("#navModal button").addEventListener("click", function() {
        document.getElementById("navModal").classList.remove("active");
    });

    // ===== DROPDOWN PROFIL ===== //
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdown = document.querySelector('.dropdown');

    if (dropdownToggle && dropdown) {
        dropdownToggle.addEventListener('click', function (event) {
            event.stopPropagation(); // Empêche le clic de se propager
            dropdown.classList.toggle('active');
        });

        // Fermer si on clique en dehors
        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    }
});
