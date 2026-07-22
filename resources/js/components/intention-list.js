export default () => ({
    selected: [],
    showRejectionModal: false,
    rejectionReason: '',
    singleRejectId: null,
    lastClickedIndex: null,
    allIds: [],
    batchProcessing: false,

    init() {
        try {
            this.allIds = JSON.parse(this.$el.dataset.ids || '[]');
        } catch (e) {
            this.allIds = [];
        }
    },

    toggleAll() {
        if (this.selected.length > 0 && this.selected.length === this.allIds.length) {
            this.selected = [];
        } else {
            this.selected = [...this.allIds];
        }
    },

    handleRowClick(index, id, event) {
        if (event.target.type === 'checkbox' || event.target.closest('a') || event.target.closest('button')) return;

        if (event.shiftKey && this.lastClickedIndex !== null) {
            const start = Math.min(this.lastClickedIndex, index);
            const end = Math.max(this.lastClickedIndex, index);
            const rangeIds = this.allIds.slice(start, end + 1);
            rangeIds.forEach(rid => {
                if (!this.selected.includes(rid)) this.selected.push(rid);
            });
        }
        this.lastClickedIndex = index;
    },

    openReject(id = null) {
        this.singleRejectId = id;
        this.rejectionReason = '';
        this.showRejectionModal = true;
    },

    _getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    },

    async batchApprove() {
        if (this.selected.length === 0) return;
        const previous = [...this.selected];
        const ids = [...this.selected];

        this.selected.forEach(id => {
            const row = document.querySelector('[data-row-id="' + id + '"]');
            if (row) {
                const badge = row.querySelector('[data-status]');
                if (badge) {
                    badge.className = 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-green-100 text-green-700 border-green-200';
                    badge.textContent = 'approved';
                }
                row.classList.add('bg-green-50/50');
            }
        });
        this.selected = [];
        this.batchProcessing = true;

        try {
            const response = await fetch(this.$el.dataset.batchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this._getCsrfToken(),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ids, status: 'approved' }),
            });
            if (!response.ok) throw new Error('Request failed');
            const data = await response.json();
            this.$store.toast.trigger(data.message || 'Batch approved.', 'success');
        } catch (e) {
            previous.forEach(id => {
                const row = document.querySelector('[data-row-id="' + id + '"]');
                if (row) {
                    const badge = row.querySelector('[data-status]');
                    if (badge) {
                        badge.className = 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-amber-100 text-amber-700 border-amber-200';
                        badge.textContent = 'pending';
                    }
                    row.classList.remove('bg-green-50/50');
                }
            });
            this.selected = previous;
            this.$store.toast.trigger('Batch action failed. Please try again.', 'error');
        } finally {
            this.batchProcessing = false;
        }
    },

    async submitReject() {
        if (this.singleRejectId) {
            try {
                const statusUrl = this.$el.dataset.statusUrl.replace(':id', this.singleRejectId);
                const response = await fetch(statusUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this._getCsrfToken(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status: 'rejected', rejection_reason: this.rejectionReason }),
                });
                if (!response.ok) throw new Error('Request failed');
                const row = document.querySelector('[data-row-id="' + this.singleRejectId + '"]');
                if (row) {
                    const badge = row.querySelector('[data-status]');
                    if (badge) {
                        badge.className = 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-red-100 text-red-700 border-red-200';
                        badge.textContent = 'rejected';
                    }
                }
                this.showRejectionModal = false;
                this.rejectionReason = '';
                this.singleRejectId = null;
                this.$store.toast.trigger('Intention rejected.', 'success');
            } catch (e) {
                this.$store.toast.trigger('Action failed. Please try again.', 'error');
            }
        } else if (this.selected.length > 0) {
            const previous = [...this.selected];
            const ids = [...this.selected];
            this.batchProcessing = true;
            this.showRejectionModal = false;

            try {
                const response = await fetch(this.$el.dataset.batchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this._getCsrfToken(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ ids, status: 'rejected', rejection_reason: this.rejectionReason }),
                });
                if (!response.ok) throw new Error('Request failed');
                const data = await response.json();
                ids.forEach(id => {
                    const row = document.querySelector('[data-row-id="' + id + '"]');
                    if (row) {
                        const badge = row.querySelector('[data-status]');
                        if (badge) {
                            badge.className = 'inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-red-100 text-red-700 border-red-200';
                            badge.textContent = 'rejected';
                        }
                    }
                });
                this.selected = [];
                this.rejectionReason = '';
                this.singleRejectId = null;
                this.$store.toast.trigger(data.message || 'Batch rejected.', 'success');
            } catch (e) {
                this.selected = previous;
                this.$store.toast.trigger('Batch action failed. Please try again.', 'error');
            } finally {
                this.batchProcessing = false;
            }
        }
    }
});
