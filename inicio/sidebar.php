<div class="sidebar">
    <div class="logo">
    <h1 style="font-size: px;" style="width: 100px;" style="height: 30px; " style="max-width: 10%; ">GESTION DE INVENTARIO</h1>
    </div>
    <ul>
        <li>
            <a href="perfil.php">
                <i class="fas fa-user"></i> Perfil
            </a>
        </li>
        <li>
            <a href="home.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="inventario.php">
                <i class="fas fa-boxes"></i> Inventario
            </a>
        </li>
        <li>
            <a href="mantenimiento.php">
                <i class="fas fa-tools"></i> Mantenimiento
            </a>
        </li>
        <li>
            <a href="generar_reporte.php">
                <i class="fas fa-chart-line"></i> Reportes
            </a>
        </li>
        <li>
            <a href="configuracion.php">
                <i class="fas fa-cog"></i> Informacion
            </a>
        </li>
        <li>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </li>
        <li>
        <div class="calendar" id="calendar">
        <div class="calendar-header">
            <button id="prev">«</button>
            <span id="month-year"></span>
            <button id="next">»</button>
        </div>
        <div class="days">
            <!-- Días se generarán dinámicamente -->
        </div>
    </div>

    <script>
        const calendar = document.getElementById('calendar');
        const daysContainer = calendar.querySelector('.days');
        const monthYear = calendar.querySelector('#month-year');
        const prevButton = calendar.querySelector('#prev');
        const nextButton = calendar.querySelector('#next');

        const now = new Date();
        let currentMonth = now.getMonth();
        let currentYear = now.getFullYear();

        const monthNames = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];
        const dayNames = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];

        function renderCalendar(month, year) {
            daysContainer.innerHTML = ""; // Limpiar los días
            monthYear.textContent = `${monthNames[month]} ${year}`;

            // Días de la semana
            dayNames.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.classList.add('day-header');
                dayHeader.textContent = day;
                daysContainer.appendChild(dayHeader);
            });

            // Fecha del primer día del mes
            const firstDay = new Date(year, month, 1).getDay();

            // Número total de días en el mes
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Días en blanco antes del primer día
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                daysContainer.appendChild(emptyDay);
            }

            // Días del mes
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('day');
                dayElement.textContent = day;

                // Marcar el día actual
                if (
                    day === now.getDate() &&
                    month === now.getMonth() &&
                    year === now.getFullYear()
                ) {
                    dayElement.classList.add('today');
                }

                daysContainer.appendChild(dayElement);
            }
        }

        function changeMonth(direction) {
            currentMonth += direction;

            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }

            renderCalendar(currentMonth, currentYear);
        }

        prevButton.addEventListener('click', () => changeMonth(-1));
        nextButton.addEventListener('click', () => changeMonth(1));

        // Renderizar el calendario inicial
        renderCalendar(currentMonth, currentYear);
    </script>
            <style>
            /* Calendario */
                /* Calendario */
                    .calendar {
                        width: 210px;
                        background: #ffffff;
                        color: #34495e;
                        position: relative;
                        left: 1px; /* Ajusta este valor según la distancia deseada */
                        border-radius: 10px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
                        padding: 25px; /* Ajuste del padding */
                        text-align: center;
                        font-family: Arial, sans-serif;
                    }

                    /* Encabezado del calendario */
                    .calendar-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 5px; /* Ajuste del margen */
                    }

                    .calendar-header button {
                        background: #7A7474;
                        color: #ffffff;
                        border: none;
                        padding: 5px 10px;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background 0.3s ease;
                    }

                    .calendar-header button:hover {
                        background: #BA8148;
                    }

                    /* Días del calendario */
                    .days {
                        display: grid;
                        grid-template-columns: repeat(7, 1fr); /* 7 columnas */
                        gap: 1px; /* Espaciado ajustado */
                    }

                    .day {
                        padding: 8px; /* Tamaño consistente */
                        background: #7A7474;
                        border-radius: 5px;
                        color: #ffffff;
                        transition: background 0.3s ease;
                        text-align: center;
                        font-size: 14px; /* Ajuste del tamaño del texto */
                    }

                    .day:hover {
                        background: #BA8148;
                        cursor: pointer;
                    }

                    /* Encabezado de los días (DOM, LUN, etc.) */
                    .day-header {
                        font-weight: bold;
                        color: #34495e;
                        text-transform: uppercase;
                        font-size: 13px; /* Ajuste del tamaño del encabezado */
                    }

                    /* Día actual */
                    .day.today {
                        background: #461615;
                        color: #ffffff;
                        font-weight: bold;
                    }

                    /* Espaciado general */
                    .days > .day, .days > .day-header {
                        width: 90%; /* Asegura que ocupe todo el espacio de su celda */
                        box-sizing: border-box; /* Incluye padding y border en las dimensiones */
                    }

            </style>

        </li>



    </ul>
    









</div>
