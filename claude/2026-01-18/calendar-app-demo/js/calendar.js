// calendar.js - Calendar grid rendering logic

const Calendar = {
    currentDate: new Date(),

    /**
     * Initialize the calendar
     */
    init() {
        this.currentDate = new Date();
        this.render();
        this.bindNavigation();
    },

    /**
     * Render the calendar for the current month
     */
    render() {
        this.updateHeader();
        this.renderDays();
    },

    /**
     * Update the month/year header
     */
    updateHeader() {
        const header = document.getElementById('current-month');
        const options = { month: 'long', year: 'numeric' };
        header.textContent = this.currentDate.toLocaleDateString('en-US', options);
    },

    /**
     * Render all day cells for the current month view
     */
    renderDays() {
        const grid = document.getElementById('days-grid');
        grid.innerHTML = '';

        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();

        // First day of the month (0 = Sunday, 6 = Saturday)
        const firstDay = new Date(year, month, 1).getDay();

        // Days in current month
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Days in previous month
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        // Today's date for comparison
        const today = new Date();
        const todayStr = this.formatDate(today);

        // Calculate total cells needed (6 rows x 7 days = 42)
        const totalCells = 42;

        for (let i = 0; i < totalCells; i++) {
            const cell = document.createElement('div');
            cell.className = 'day-cell min-h-20 sm:min-h-24 md:min-h-28 p-2 md:p-3 border-r border-b border-gray-200 cursor-pointer relative overflow-hidden hover:bg-gray-50 focus:outline-2 focus:outline-blue-500 focus:-outline-offset-2';
            cell.tabIndex = 0;

            let dayNum, dateStr, isOtherMonth = false;

            if (i < firstDay) {
                // Previous month's trailing days
                dayNum = daysInPrevMonth - firstDay + i + 1;
                const prevMonth = month === 0 ? 11 : month - 1;
                const prevYear = month === 0 ? year - 1 : year;
                dateStr = this.formatDate(new Date(prevYear, prevMonth, dayNum));
                isOtherMonth = true;
            } else if (i >= firstDay + daysInMonth) {
                // Next month's leading days
                dayNum = i - firstDay - daysInMonth + 1;
                const nextMonth = month === 11 ? 0 : month + 1;
                const nextYear = month === 11 ? year + 1 : year;
                dateStr = this.formatDate(new Date(nextYear, nextMonth, dayNum));
                isOtherMonth = true;
            } else {
                // Current month days
                dayNum = i - firstDay + 1;
                dateStr = this.formatDate(new Date(year, month, dayNum));
            }

            cell.dataset.date = dateStr;

            if (isOtherMonth) {
                cell.classList.add('other-month', 'text-gray-400', 'bg-gray-50');
            }

            if (dateStr === todayStr) {
                cell.classList.add('today', 'bg-blue-100');
            }

            // Day number
            const dayNumber = document.createElement('span');
            dayNumber.className = 'day-number font-medium text-sm';
            dayNumber.textContent = dayNum;
            cell.appendChild(dayNumber);

            // Event list container
            const eventList = document.createElement('div');
            eventList.className = 'event-list mt-1';
            cell.appendChild(eventList);

            // Render events for this day
            this.renderEventsForDay(eventList, dateStr);

            // Click handler to add event
            cell.addEventListener('click', (e) => {
                if (!e.target.classList.contains('event-item')) {
                    Modal.openForAdd(dateStr);
                }
            });

            // Keyboard support
            cell.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    Modal.openForAdd(dateStr);
                }
            });

            grid.appendChild(cell);
        }
    },

    /**
     * Render events for a specific day
     * @param {HTMLElement} container - Event list container
     * @param {string} date - Date string YYYY-MM-DD
     */
    renderEventsForDay(container, date) {
        const events = Storage.getEventsByDate(date);

        events.forEach(event => {
            const eventEl = document.createElement('div');
            eventEl.className = 'event-item bg-blue-500 text-white text-xs sm:text-sm px-1.5 py-0.5 rounded mb-0.5 whitespace-nowrap overflow-hidden text-ellipsis cursor-pointer hover:bg-blue-600';
            eventEl.dataset.eventId = event.id;

            // Show time if available
            const timeStr = event.time ? `${event.time} ` : '';
            eventEl.textContent = timeStr + event.title;
            eventEl.title = event.title; // Tooltip for truncated text

            eventEl.addEventListener('click', (e) => {
                e.stopPropagation();
                Modal.openForEdit(event.id);
            });

            container.appendChild(eventEl);
        });
    },

    /**
     * Bind navigation button handlers
     */
    bindNavigation() {
        document.getElementById('prev-month').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.render();
        });

        document.getElementById('next-month').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.render();
        });
    },

    /**
     * Format a Date object as YYYY-MM-DD
     * @param {Date} date
     * @returns {string}
     */
    formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
};
