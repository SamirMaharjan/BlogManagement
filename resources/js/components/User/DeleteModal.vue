<template>
    <div v-if="isModalOpen" class="fixed inset-0 z-50 bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md w-1/3">
            <h2 class="text-lg font-medium text-gray-900">Are you sure you want to delete this user?</h2>

            <div class="mt-4">
                <p class="text-gray-700">This action is irreversible. The user will be permanently deleted.</p>
                <p class="font-bold text-gray-800 mt-2">User: {{ form.name }}</p>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                <button type="button" @click="submitForm" class="px-4 py-2 bg-red-500 text-white rounded-md ml-3"
                    :disabled="isSubmitting">
                    {{ isSubmitting ? 'Deleting...' : 'Delete' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';  // Make sure you import the styles

// Enable withCredentials to send cookies with the request
axios.defaults.withCredentials = true;

export default {
    name: 'DeleteModal',
    data() {
        return {
            isModalOpen: false,
            isSubmitting: false,
            errors: {},
            form: {
                id: null,
                name: ''
            }
        };
    },
    methods: {
        openDeleteModal(user) {
            this.isModalOpen = true;
            this.errors = {};

            if (user) {
                this.form = {
                    id: user.id,
                    name: user.name
                };
            } else {
                this.form = {
                    id: null,
                    name: ''
                };
            }
        },
        closeModal() {
            this.isModalOpen = false;
            this.errors = {};
        },
        async submitForm() {
            this.isSubmitting = true;
            this.errors = {};

            try {
                const headers = {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };

                // Send DELETE request to the backend to delete the user
                const response = await axios.delete(`/user/${this.form.id}`, { headers });

                // this.$emit('user-deleted', this.form.id);
                toastr.success(response.data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                this.closeModal();


            } catch (error) {
                console.error('Error deleting user:', error);

                // Handle errors (e.g., if user is not found or any server issue)
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    toastr.error('An error occurred while deleting the user.');
                }
            } finally {
                this.isSubmitting = false;
            }
        }
    }
};
</script>

<style scoped>
/* Basic styles for modal, you can customize as needed */
</style>
