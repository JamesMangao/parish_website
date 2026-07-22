export default () => ({
    showAddModal: false,
    showEditModal: false,
    currentUser: {},
    originalRole: '',
    editUser(user) {
        this.currentUser = { ...user };
        this.originalRole = user.role;
        this.showEditModal = true;
    },
    submitEdit() {
        if (this.currentUser.role !== this.originalRole) {
            this.$store.confirm.open({
                title: 'Change User Role?',
                message: 'Changing role from "' + this.originalRole.replace('_', ' ') + '" to "' + this.currentUser.role.replace('_', ' ') + '" will alter this user\'s permissions immediately.',
                onConfirm: () => this.$refs.editForm.submit(),
                type: 'primary',
                confirmText: 'Update Role'
            });
        } else {
            this.$refs.editForm.submit();
        }
    }
});
