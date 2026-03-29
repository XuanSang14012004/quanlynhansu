// calendar.js
let tasks = [];

document.addEventListener('DOMContentLoaded', function () {
    fetchTasks();
});

async function fetchTasks() {
    try {
        const response = await fetch('/api/calendar-tasks');
        tasks = await response.json();
    } catch (error) {
        console.error('Lỗi lấy dữ liệu:', error);
        tasks = [];
    }
    renderCalendar();
}

function renderCalendar() {
    const calendar = document.getElementById('calendar');
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();

    // Update month/year label in header (the portlet action span)
    const monthYearEl = document.getElementById('currentMonthYear');
    if (monthYearEl) {
        monthYearEl.innerText = `Tháng ${month + 1} / ${year}`;
    }

    const firstDayOfMonth = new Date(year, month, 1);
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // getDay(): 0=Sunday, 1=Monday, ..., 6=Saturday
    // In the screenshot CN (Sunday) is the first column (index 0)
    // So startOffset = firstDayOfMonth.getDay() directly (0 = CN = column 0)
    const startOffset = firstDayOfMonth.getDay(); // 0–6 where 0 = Sunday

    // --- Header ---
    const weekdays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

    let html = `
        <div class="calendar-header">
            <h2>Tháng ${month + 1} ${year}</h2>
        </div>
        <div class="calendar-weekdays">
            ${weekdays.map(d => `<div class="weekday-label">${d}</div>`).join('')}
        </div>
        <div class="calendar-grid">
    `;

    // Empty cells before first day
    for (let i = 0; i < startOffset; i++) {
        html += `<div class="calendar-day empty"></div>`;
    }

    // Day cells
    for (let day = 1; day <= daysInMonth; day++) {
        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        // Filter tasks spanning this day
        const dayTasks = tasks.filter(t => dateString >= t.startDate && dateString <= t.endDate);

        const tasksHtml = dayTasks.map(t => {
            const statusClass = t.status == 1 ? 'status-1' : 'status-0';
            return `<div class="task-item ${statusClass}" title="${t.title}">${t.title}</div>`;
        }).join('');

        html += `
            <div class="calendar-day">
                <div class="day-number">${day}</div>
                <div class="task-list">${tasksHtml}</div>
            </div>
        `;
    }

    // Fill remaining cells to complete last row
    const totalCells = startOffset + daysInMonth;
    const remainder = totalCells % 7;
    if (remainder !== 0) {
        for (let i = 0; i < 7 - remainder; i++) {
            html += `<div class="calendar-day empty"></div>`;
        }
    }

    html += `</div>`;
    calendar.innerHTML = html;
}