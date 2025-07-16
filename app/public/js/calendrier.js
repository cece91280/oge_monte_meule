document.addEventListener("DOMContentLoaded", function () {
    // ======= CALENDRIER =======
    const monthYear = document.getElementById("monthYear");
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");
    const calendarBody = document.querySelector("#calendarTable tbody");

    let currentDate = new Date(2025, 5); // Juin = mois 5 (0-indexed)
    let reservedDates = [];
    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();

        // Mettre à jour le titre
        const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        monthYear.textContent = `${monthNames[month]} ${year}`;

        // Calcul début et fin du mois
        const firstDay = new Date(year, month, 1).getDay(); // 0 = Dimanche
        const startDay = (firstDay === 0) ? 7 : firstDay;
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Nettoyage
        calendarBody.innerHTML = "";

        let day = 1;
        let started = false;

        for (let i = 0; i < 5; i++) {
            const row = document.createElement("tr");
            for (let j = 1; j <= 7; j++) {
                const cell = document.createElement("td");
                if (!started && j === startDay) {
                    started = true;
                }
                if (started && day <= daysInMonth) {
                    cell.classList.add("calendar-day");
                    cell.textContent = day;

                    // Exemple de jours réservés ou disponibles (à adapter dynamiquement)
                    const dateStr = `${year}-${month + 1}-${day}`;
                    const jsDate = new Date(year, month, day);
                    if (jsDate.getDay() === 0){
                        cell.classList.add("reserved");
                        cell.title = "Indisponible le dimanche"
                    }
                    else if (reservedDates.includes(dateStr)) {
                        cell.classList.add("reserved");
                        cell.title = "Date déjà réservée"
                    } else {
                        cell.classList.add("disponible");
                    }
                    if (cell.classList.contains("reserved")){
                        // Désactive le clic, grise la case
                        cell.style.pointerEvents = "none";
                        cell.style.opacity = "0.6"
                    }

                    day++;
                }

                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
        }
    }

    fetch('/api/reserved-days')
        .then(response => response.json())
        .then(data => {
            reservedDates = data;
            renderCalendar(currentDate);
        })
        .catch(error => {
            console.error("Erreur lors du chargement des dates réservées :", error);
            renderCalendar(currentDate);
        });

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

})