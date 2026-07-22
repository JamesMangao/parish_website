export const settingsForm = () => ({
    MAX_CONTACTS: 10,
    MAX_TIMELINE: 30,
    qrPreview: null,
    priestPreview: null,
    assistantPriestPreview: null,
    _previewUrls: {},

    init() {
        const el = document.getElementById('settings-previews-data');
        if (el) {
            try {
                const d = JSON.parse(el.textContent);
                this.qrPreview = d.qrUrl || null;
                this.priestPreview = d.priestUrl || null;
                this.assistantPriestPreview = d.assistantPriestUrl || null;
            } catch {}
        }
    },

    handleFileUpload(e, key, label) {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 1.8 * 1024 * 1024) {
            this.$store.toast.trigger(`${label} is too large. Maximum size is 1.8MB.`, 'error');
            e.target.value = '';
            return;
        }
        if (this._previewUrls[key]) URL.revokeObjectURL(this._previewUrls[key]);
        this._previewUrls[key] = URL.createObjectURL(file);
        this[key] = this._previewUrls[key];
    },

    revokePreviews() {
        Object.values(this._previewUrls).forEach(url => URL.revokeObjectURL(url));
    }
});

export const contactNumbers = () => ({
    numbers: [''],

    init() {
        const el = document.getElementById('contact-numbers-data');
        if (el) {
            try {
                const parsed = JSON.parse(el.textContent);
                this.numbers = Array.isArray(parsed) && parsed.length ? parsed : [''];
            } catch {}
        }
    },

    addNumber() {
        if (this.numbers.length < 10) this.numbers.push('');
    },

    removeNumber(index) {
        if (this.numbers.length > 1) this.numbers.splice(index, 1);
    }
});

export const timelineManager = () => ({
    entries: [],

    init() {
        const el = document.getElementById('timeline-entries-data');
        if (el) {
            try {
                const parsed = JSON.parse(el.textContent);
                this.entries = Array.isArray(parsed) && parsed.length ? parsed : [{ year: '', badge: '', title: '', short: '', full: '' }];
            } catch {}
        }
    },

    addEntry() {
        if (this.entries.length >= 30) {
            this.$store.toast.trigger('Maximum 30 timeline entries allowed.', 'error');
            return;
        }
        this.entries.push({ year: '', badge: '', title: '', short: '', full: '' });
    },

    removeEntry(index) {
        if (this.entries.length <= 1) {
            this.$store.toast.trigger('At least one timeline entry is required.', 'error');
            return;
        }
        this.entries.splice(index, 1);
    },

    moveUp(index) {
        if (index === 0) return;
        const item = this.entries.splice(index, 1)[0];
        this.entries.splice(index - 1, 0, item);
    },

    moveDown(index) {
        if (index >= this.entries.length - 1) return;
        const item = this.entries.splice(index, 1)[0];
        this.entries.splice(index + 1, 0, item);
    }
});
