<template>
    <div class="flex justify-end">
      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <!-- Previous Page Link -->
        <button 
          :disabled="!links.prev"
          @click="selectPage(meta.current_page - 1)"
          class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !links.prev }"
        >
          <span class="sr-only">Previous</span>
          &lsaquo;
        </button>
        
        <!-- First Page -->
        <button
          @click="selectPage(1)"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
          :class="meta.current_page === 1 ? 'bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-700 hover:bg-gray-50'"
        >
          1
        </button>
        
        <!-- Ellipsis for many pages -->
        <span v-if="meta.last_page > 5 && meta.current_page > 3" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
          ...
        </span>
        
        <!-- Middle Pages -->
        <template v-for="page in middlePages" :key="page">
          <button
            @click="selectPage(page)"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
            :class="meta.current_page === page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-700 hover:bg-gray-50'"
          >
            {{ page }}
          </button>
        </template>
        
        <!-- Ellipsis for many pages -->
        <span v-if="meta.last_page > 5 && meta.current_page < meta.last_page - 2" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
          ...
        </span>
        
        <!-- Last Page (if more than 1 page) -->
        <button
          v-if="meta.last_page > 1"
          @click="selectPage(meta.last_page)"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
          :class="meta.current_page === meta.last_page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-700 hover:bg-gray-50'"
        >
          {{ meta.last_page }}
        </button>
        
        <!-- Next Page Link -->
        <button 
          :disabled="!links.next"
          @click="selectPage(meta.current_page + 1)"
          class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !links.next }"
        >
          <span class="sr-only">Next</span>
          &rsaquo;
        </button>
      </nav>
    </div>
  </template>
  
  <script>
  export default {
    name: 'PaginationLinks',
    props: {
      meta: {
        type: Object,
        required: true
      },
      links: {
        type: Object,
        required: true
      }
    },
    computed: {
      middlePages() {
        if (this.meta.last_page <= 5) {
          // If 5 or fewer pages, show all pages except first and last
          return Array.from({ length: this.meta.last_page - 2 }, (_, i) => i + 2);
        }
        
        // For many pages, show a window around current page
        let start = Math.max(2, this.meta.current_page - 1);
        let end = Math.min(this.meta.last_page - 1, this.meta.current_page + 1);
        
        // Adjust window to always show 3 pages when possible
        if (this.meta.current_page <= 3) {
          end = Math.min(4, this.meta.last_page - 1);
        } else if (this.meta.current_page >= this.meta.last_page - 2) {
          start = Math.max(2, this.meta.last_page - 3);
        }
        
        return Array.from({ length: end - start + 1 }, (_, i) => i + start);
      }
    },
    methods: {
      selectPage(page) {
        // Don't emit if clicking on current page or invalid pages
        if (page === this.meta.current_page || 
            (page < 1) || 
            (page > this.meta.last_page)) {
          return;
        }
        
        this.$emit('page-selected', page);
      }
    }
  };
  </script>