import './bootstrap';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import UserModal from './components/User/UserModal.vue'; // Adjust path if needed
import DeleteModal from './components/User/DeleteModal.vue'; // Adjust path if needed
import BlogModal from './components/Blog/BlogModal.vue';
import DeleteBlogModal from './components/Blog/DeleteBlogModal.vue';
import BlogDetailModal from './components/Blog/BlogDetailModal.vue';

// Create a Vue app instance and register the component globally
const app = createApp({});
createApp({
    components: {
        UserModal,
        DeleteModal,
        BlogModal,
        BlogDetailModal,
        DeleteBlogModal

    }
  }).mount('#app');
// app.component('user-modal', UserModal);
// app.mount('#app');

window.Alpine = Alpine;
Alpine.start();
