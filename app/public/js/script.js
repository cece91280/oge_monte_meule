// Toggle menu for burger icon
document.addEventListener("DOMContentLoaded", function () {
    // ======= BURGER =======
    document.getElementById("burgerMenu").addEventListener("click", function() {
        document.getElementById("navModal").classList.add("active");
    });

    document.querySelector("#navModal button").addEventListener("click", function() {
        document.getElementById("navModal").classList.remove("active");
    });

    // ======= CALENDRIER =======
    const monthYear = document.getElementById("monthYear");
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");
    const calendarBody = document.querySelector("#calendarTable tbody");

    let currentDate = new Date(2025, 5); // Juin = mois 5 (0-indexed)

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();

        // Mettre à jour le titre
        const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        monthYear.textContent = `${monthNames[month]} ${year}`;

        // Calcul début et fin du mois
        const firstDay = new Date(year, month, 1).getDay(); // 0 = Dimanche
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Nettoyage
        calendarBody.innerHTML = "";

        let day = 1;
        let started = false;

        for (let i = 0; i < 6; i++) {
            const row = document.createElement("tr");
            for (let j = 1; j <= 7; j++) {
                const cell = document.createElement("td");
                if (!started && ((j === 1 && firstDay === 0) || j === firstDay)) {
                    started = true;
                }
                if (started && day <= daysInMonth) {
                    cell.classList.add("calendar-day");
                    cell.textContent = day;

                    // Exemple de jours réservés ou disponibles (à adapter dynamiquement)
                    const dateStr = `${year}-${month + 1}-${day}`;
                    if (["2025-6-6", "2025-6-16", "2025-6-26"].includes(dateStr)) {
                        cell.classList.add("reserved");
                    } else if (["2025-6-25"].includes(dateStr)) {
                        cell.classList.add("disponible");
                    }

                    day++;
                }

                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
        }
    }

    // Navigation
    prevBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    renderCalendar(currentDate);
});
