<template>
    <div v-if="isModalOpen" class="fixed inset-0 z-50 bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md w-1/3">
            <h2 class="text-lg font-medium text-gray-900">{{ isEdit ? 'Edit Blog' : 'Create Blog' }}</h2>
            <form @submit.prevent="submitForm">

                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input v-model="form.title" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        :class="{ 'border-red-500': errors.title }">
                    <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title[0] }}</p>
                </div>

                <!-- Content -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea v-model="form.content"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        :class="{ 'border-red-500': errors.content }"></textarea>
                    <p v-if="errors.content" class="mt-1 text-sm text-red-600">{{ errors.content[0] }}</p>
                </div>

                <!-- Existing Images (Edit Mode) -->
                <div v-if="isEdit && form.images.length" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Existing Images</label>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <div v-for="(image, index) in form.images" :key="image.id" class="relative">
                            <img :src="image.url" alt="Blog Image" class="w-24 h-24 object-cover rounded-md border">
                            <button type="button"
                                @click="removeExistingImage(image.id, index)"
                                class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full text-xs">
                                ✖
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Image Dropzone -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                    <div id="dropzone" class="dropzone border-dashed border-2 border-gray-300 p-4 text-center">
                        <p>Drop an image here or click to upload</p>
                    </div>
                    <p v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image[0] }}</p>
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
import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';
import 'toastr/build/toastr.min.css';

export default {
    name: 'BlogModal',
    data() {
        return {
            isModalOpen: false,
            isEdit: false,
            isSubmitting: false,
            errors: {},
            form: {
                id: null,
                title: '',
                content: '',
                images: [], // Stores existing images
                image: null, // Stores new uploaded file
            },
            dropzoneInstance: null,
            removedImages: [], // Store IDs of removed images
        };
    },
    methods: {
        openModal(blog = null) {
            this.isModalOpen = true;
            this.errors = {};
            if (blog) {
                this.isEdit = true;
                this.form = { id: blog.id, title: blog.title, content: blog.content, images: blog.images || [] };
                this.removedImages = [];
            } else {
                this.isEdit = false;
                this.form = { id: null, title: '', content: '', images: [], image: null };
                this.removedImages = [];
            }

            this.$nextTick(() => {
                this.initializeDropzone();
            });
        },
        closeModal() {
            this.isModalOpen = false;
            this.errors = {};
        },
        initializeDropzone() {
            if (this.dropzoneInstance) {
                this.dropzoneInstance.destroy();
            }

            this.dropzoneInstance = new Dropzone("#dropzone", {
                url: "/api/upload-image", // Not used since we prevent auto upload
                maxFiles: 5,
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                autoProcessQueue: false,
                dictDefaultMessage: "Drop an image here or click to upload",
            });

            this.dropzoneInstance.on("addedfile", (file) => {
                this.form.image = file;
            });

            this.dropzoneInstance.on("removedfile", () => {
                this.form.image = null;
            });
        },
        removeExistingImage(imageId, index) {
            this.removedImages.push(imageId);
            this.form.images.splice(index, 1);
        },
        async submitForm() {
            this.isSubmitting = true;
            this.errors = {};

            try {
                const formData = new FormData();
                formData.append('title', this.form.title);
                formData.append('content', this.form.content);

                // Append new images
                if (this.form.image) {
                    formData.append('image', this.form.image);
                }

                // Append removed images
                this.removedImages.forEach((id) => {
                    formData.append('remove_images[]', id);
                });

                let response;
                if (this.isEdit) {
                    formData.append('_method', 'PUT');
                    response = await axios.post(`/api/blog/${this.form.id}`, formData);
                } else {
                    response = await axios.post('/api/blog', formData);
                }

                toastr.success(response.data.message);
                // setTimeout(() => {
                //     window.location.reload();
                // }, 1000);

                this.closeModal();
            } catch (error) {
                console.error('Error saving blog:', error);
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    toastr.error('An error occurred while saving the blog.');
                }
            } finally {
                this.isSubmitting = false;
            }
        }
    }
};
</script>

<style scoped>
/* Dropzone Styling */
.dropzone {
    cursor: pointer;
    background-color: #f8f9fa;
    color: #6c757d;
    border-radius: 5px;
}
</style>
