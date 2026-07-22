export const settingsForm = (initialPreviews = {}) => ({
    MAX_CONTACTS: 10,
    MAX_TIMELINE: 30,
    qrPreview: initialPreviews.qrUrl || null,
    priestPreview: initialPreviews.priestUrl || null,
    assistantPriestPreview: initialPreviews.assistantPriestUrl || null,
    _previewUrls: {},

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

export const contactNumbers = (initialNumbers = ['']) => ({
    numbers: initialNumbers,

    addNumber() {
        if (this.numbers.length < 10) this.numbers.push('');
    },

    removeNumber(index) {
        if (this.numbers.length > 1) this.numbers.splice(index, 1);
    }
});

export const timelineManager = (initialEntries = []) => ({
    entries: initialEntries,

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
