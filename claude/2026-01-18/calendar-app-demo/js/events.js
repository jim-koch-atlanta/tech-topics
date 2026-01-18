// events.js - Event CRUD operations

const Events = {
    /**
     * Create a new event
     * @param {Object} eventData - Event data from form
     * @returns {Object|null} Created event or null if validation failed
     */
    create(eventData) {
        const validation = Validation.validateEvent(eventData);

        if (!validation.valid) {
            Validation.showErrors(validation.errors);
            return null;
        }

        const event = Storage.saveEvent({
            title: eventData.title.trim(),
            date: eventData.date,
            time: eventData.time || '',
            description: eventData.description ? eventData.description.trim() : ''
        });

        Calendar.render();
        return event;
    },

    /**
     * Update an existing event
     * @param {string} id - Event ID
     * @param {Object} eventData - Updated event data
     * @returns {Object|null} Updated event or null if validation failed
     */
    update(id, eventData) {
        const validation = Validation.validateEvent(eventData);

        if (!validation.valid) {
            Validation.showErrors(validation.errors);
            return null;
        }

        const event = Storage.saveEvent({
            id,
            title: eventData.title.trim(),
            date: eventData.date,
            time: eventData.time || '',
            description: eventData.description ? eventData.description.trim() : ''
        });

        Calendar.render();
        return event;
    },

    /**
     * Delete an event
     * @param {string} id - Event ID
     * @returns {boolean} True if deleted
     */
    delete(id) {
        const result = Storage.deleteEvent(id);
        if (result) {
            Calendar.render();
        }
        return result;
    },

    /**
     * Get an event by ID
     * @param {string} id - Event ID
     * @returns {Object|null}
     */
    getById(id) {
        return Storage.getEventById(id);
    }
};
