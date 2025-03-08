<template>
    <div v-if="isModalOpen" class="fixed inset-0 z-50 bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md w-1/3">
            <h2 class="text-lg font-medium text-gray-900">{{ isEdit ? 'Edit User' : 'Create User' }}</h2>
            <form @submit.prevent="submitForm">
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        :class="{ 'border-red-500': errors.name }">
                    <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        :class="{ 'border-red-500': errors.email }">
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
                </div>

                <!-- Password - Only required for new users -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Password {{ isEdit ? '(leave blank to keep current)' : '' }}
                    </label>
                    <input v-model="form.password" type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        :class="{ 'border-red-500': errors.password }">
                    <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input v-model="form.password_confirmation" type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md ml-3"
                        :disabled="isSubmitting">
                        {{ isSubmitting ? 'Saving...' : 'Save' }}
                    </button>
                </div>
            </form>
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
    name: 'UserModal',
    data() {
        return {
            isModalOpen: false,
            isEdit: false,
            isSubmitting: false,
            errors: {},
            form: {
                id: null,
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        }
    },
    methods: {
        openModal(user = null) {
            this.isModalOpen = true;
            this.errors = {};

            if (user) {
                this.isEdit = true;
                this.form = {
                    ...user,
                    password: '',
                    password_confirmation: ''
                };
            } else {
                this.isEdit = false;
                this.form = {
                    id: null,
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
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

                let response;

                // If password is empty in edit mode, remove it from the payload
                const payload = { ...this.form };
                if (this.isEdit && !payload.password) {
                    delete payload.password;
                    delete payload.password_confirmation;
                }

                if (this.isEdit) {
                    // Update existing user
                    response = await axios.put(`/user/${this.form.id}`, payload, { headers });
                    this.$emit('user-updated', response.data);
                } else {
                    // Create new user
                    response = await axios.post('/user', payload, { headers });
                    this.$emit('user-created', response.data);
                }
                console.log(response);

                toastr.success(response.data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                // console.log('User saved:', response.data);
                this.closeModal();

            } catch (error) {
                console.error('Error saving user:', error);

                // Handle validation errors
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    toastr.success('An error occurred while saving the user.');
                }
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>

<style scoped>
/* Basic styles for modal, you can customize as needed */
</style>