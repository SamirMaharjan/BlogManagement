<template>
    <div v-if="isModalOpen" class="fixed inset-0 z-50 bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md w-1/3">
            <h2 class="text-lg font-medium text-gray-900">Blog Details</h2>
            
            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <p class="mt-1 text-gray-900">{{ blog.title }}</p>
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Content</label>
                <p class="mt-1 text-gray-900">{{ blog.content }}</p>
            </div>

            <!-- Images -->
            <div v-if="blog.images.length" class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Images</label>
                <div class="flex flex-wrap gap-2 mt-2">
                    <img v-for="image in blog.images" :key="image.id" :src="getImageUrl(image.path)" 
                        alt="Blog Image" class="w-20 h-20 object-cover rounded-md border border-gray-300">
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded-md">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BlogDetailModal',
    data() {
        return {
            isModalOpen: false,
            blog: {
                title: '',
                content: '',
                images: []
            }
        };
    },
    methods: {
        openModal(blog) {
            this.isModalOpen = true;
            this.blog = blog;
        },
        closeModal() {
            this.isModalOpen = false;
        },
        getImageUrl(path) {
            return `/storage/${path}`;
        }
    }
};
</script>

<style scoped>
/* Styles remain the same */
</style>