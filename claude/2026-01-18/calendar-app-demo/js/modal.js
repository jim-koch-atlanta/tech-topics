// modal.js - Modal management

const Modal = {
    overlay: null,
    form: null,
    titleEl: null,
    deleteBtn: null,
    editingEventId: null,

    /**
     * Initialize modal elements and event listeners
     */
    init() {
        this.overlay = document.getElementById('modal-overlay');
        this.form = document.getElementById('event-form');
        this.titleEl = document.getElementById('modal-title');
        this.deleteBtn = document.getElementById('delete-btn');

        this.bindEvents();
    },

    /**
     * Bind modal event listeners
     */
    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSave();
        });

        // Cancel button
        document.getElementById('cancel-btn').addEventListener('click', () => {
            this.close();
        });

        // Delete button
        this.deleteBtn.addEventListener('click', () => {
            this.handleDelete();
        });

        // Close on backdrop click
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.close();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.overlay.classList.contains('hidden')) {
                this.close();
            }
        });
    },

    /**
     * Open modal for adding a new event
     * @param {string} date - Pre-filled date (YYYY-MM-DD)
     */
    openForAdd(date) {
        this.editingEventId = null;
        this.titleEl.textContent = 'Add Event';
        this.deleteBtn.classList.add('hidden');

        this.form.reset();
        Validation.clearErrors();

        document.getElementById('event-date').value = date || '';
        document.getElementById('event-id').value = '';

        this.show();
        document.getElementById('event-title').focus();
    },

    /**
     * Open modal for editing an existing event
     * @param {string} eventId - Event ID to edit
     */
    openForEdit(eventId) {
        const event = Events.getById(eventId);
        if (!event) return;

        this.editingEventId = eventId;
        this.titleEl.textContent = 'Edit Event';
        this.deleteBtn.classList.remove('hidden');

        Validation.clearErrors();

        document.getElementById('event-title').value = event.title;
        document.getElementById('event-date').value = event.date;
        document.getElementById('event-time').value = event.time || '';
        document.getElementById('event-description').value = event.description || '';
        document.getElementById('event-id').value = event.id;

        this.show();
        document.getElementById('event-title').focus();
    },

    /**
     * Handle form save
     */
    handleSave() {
        const formData = {
            title: document.getElementById('event-title').value,
            date: document.getElementById('event-date').value,
            time: document.getElementById('event-time').value,
            description: document.getElementById('event-description').value
        };

        let result;
        if (this.editingEventId) {
            result = Events.update(this.editingEventId, formData);
        } else {
            result = Events.create(formData);
        }

        if (result) {
            this.close();
        }
    },

    /**
     * Handle event deletion
     */
    handleDelete() {
        if (!this.editingEventId) return;

        if (confirm('Are you sure you want to delete this event?')) {
            Events.delete(this.editingEventId);
            this.close();
        }
    },

    /**
     * Show the modal
     */
    show() {
        this.overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    },

    /**
     * Close the modal
     */
    close() {
        this.overlay.classList.add('hidden');
        document.body.style.overflow = '';
        this.editingEventId = null;
    }
};
