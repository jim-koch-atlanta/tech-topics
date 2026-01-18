// validation.js - Form validation utilities

const Validation = {
    /**
     * Validate an event object
     * @param {Object} event - Event data to validate
     * @returns {Object} { valid: boolean, errors: { field: message } }
     */
    validateEvent(event) {
        const errors = {};

        // Title: required, max 100 chars
        if (!event.title || !event.title.trim()) {
            errors.title = 'Title is required';
        } else if (event.title.length > 100) {
            errors.title = 'Title must be 100 characters or less';
        }

        // Date: required, valid format
        if (!event.date) {
            errors.date = 'Date is required';
        } else if (!this.isValidDate(event.date)) {
            errors.date = 'Please enter a valid date';
        }

        // Time: valid format if provided
        if (event.time && !this.isValidTime(event.time)) {
            errors.time = 'Please enter a valid time';
        }

        // Description: max 500 chars
        if (event.description && event.description.length > 500) {
            errors.description = 'Description must be 500 characters or less';
        }

        return {
            valid: Object.keys(errors).length === 0,
            errors
        };
    },

    /**
     * Check if a date string is valid YYYY-MM-DD format
     * @param {string} dateStr - Date string to validate
     * @returns {boolean}
     */
    isValidDate(dateStr) {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return false;
        const date = new Date(dateStr + 'T00:00:00');
        return !isNaN(date.getTime());
    },

    /**
     * Check if a time string is valid HH:MM format
     * @param {string} timeStr - Time string to validate
     * @returns {boolean}
     */
    isValidTime(timeStr) {
        if (!/^\d{2}:\d{2}$/.test(timeStr)) return false;
        const [hours, minutes] = timeStr.split(':').map(Number);
        return hours >= 0 && hours <= 23 && minutes >= 0 && minutes <= 59;
    },

    /**
     * Display validation errors in the form
     * @param {Object} errors - { field: message } object
     */
    showErrors(errors) {
        // Clear previous errors
        this.clearErrors();

        for (const [field, message] of Object.entries(errors)) {
            const errorEl = document.getElementById(`${field}-error`);
            const inputEl = document.getElementById(`event-${field}`);

            if (errorEl) {
                errorEl.textContent = message;
            }
            if (inputEl) {
                inputEl.classList.add('error', 'border-red-500');
            }
        }
    },

    /**
     * Clear all validation errors from the form
     */
    clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        const inputs = document.querySelectorAll('.form-group input, .form-group textarea');

        errorMessages.forEach(el => el.textContent = '');
        inputs.forEach(el => el.classList.remove('error', 'border-red-500'));
    }
};
