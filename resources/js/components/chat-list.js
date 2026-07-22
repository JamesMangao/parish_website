export default () => ({
    status: '',
    search: '',
    init() {
        this.status = this.$el.dataset.status || '';
        this.search = this.$el.dataset.search || '';
        const url = this.$el.dataset.url;
        if (!url) return;
        setInterval(() => {
            fetch(url + '?status=' + encodeURIComponent(this.status) + '&search=' + encodeURIComponent(this.search || ''))
                .then(r => r.json())
                .then(d => {
                    const oldTbody = document.getElementById('sessions-tbody');
                    if (oldTbody) oldTbody.outerHTML = d.html;
                    const pagWrap = document.getElementById('sessions-pagination-wrap');
                    if (pagWrap) pagWrap.outerHTML = '<div id="sessions-pagination-wrap">' + d.pagination + '</div>';
                })
                .catch(() => {});
        }, 15000);
    }
});
