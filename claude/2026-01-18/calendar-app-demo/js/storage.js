// storage.js - localStorage operations for calendar events

const STORAGE_KEY = 'calendar_events';

const Storage = {
    /**
     * Get all events from localStorage
     * @returns {Array} Array of event objects
     */
    getEvents() {
        try {
            const data = localStorage.getItem(STORAGE_KEY);
            if (!data) return [];
            const events = JSON.parse(data);
            return Array.isArray(events) ? events : [];
        } catch (e) {
            console.error('Error reading events from localStorage:', e);
            return [];
        }
    },

    /**
     * Save a new event or update existing one
     * @param {Object} event - Event object to save
     * @returns {Object} The saved event with id and timestamps
     */
    saveEvent(event) {
        const events = this.getEvents();
        const now = new Date().toISOString();

        if (event.id) {
            // Update existing event
            const index = events.findIndex(e => e.id === event.id);
            if (index !== -1) {
                events[index] = {
                    ...events[index],
                    ...event,
                    updatedAt: now
                };
                this._persist(events);
                return events[index];
            }
        }

        // Create new event
        const newEvent = {
            ...event,
            id: crypto.randomUUID(),
            createdAt: now,
            updatedAt: now
        };
        events.push(newEvent);
        this._persist(events);
        return newEvent;
    },

    /**
     * Delete an event by ID
     * @param {string} id - Event ID to delete
     * @returns {boolean} True if deleted, false if not found
     */
    deleteEvent(id) {
        const events = this.getEvents();
        const filtered = events.filter(e => e.id !== id);
        if (filtered.length < events.length) {
            this._persist(filtered);
            return true;
        }
        return false;
    },

    /**
     * Get events for a specific date
     * @param {string} date - Date in YYYY-MM-DD format
     * @returns {Array} Events on that date
     */
    getEventsByDate(date) {
        return this.getEvents().filter(e => e.date === date);
    },

    /**
     * Get a single event by ID
     * @param {string} id - Event ID
     * @returns {Object|null} Event object or null
     */
    getEventById(id) {
        return this.getEvents().find(e => e.id === id) || null;
    },

    /**
     * Persist events array to localStorage
     * @private
     */
    _persist(events) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(events));
        } catch (e) {
            console.error('Error saving events to localStorage:', e);
        }
    }
};
