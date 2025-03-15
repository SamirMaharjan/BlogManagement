<template>
    <div>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
              <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">User List</h2>
                <button 
                  @click="openCreateModal" 
                  class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
                >
                  + Create User
                </button>
              </div>
  
              <table v-if="users.length > 0" class="min-w-full table-auto border-collapse">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="px-2 py-2 border-b text-center">ID</th>
                    <th class="px-2 py-2 border-b text-center">Name</th>
                    <th class="px-2 py-2 border-b text-center">Email</th>
                    <th class="px-2 py-2 border-b text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users" :key="user.id">
                    <td class="px-2 py-2 border-b text-center">{{ user.id }}</td>
                    <td class="px-2 py-2 border-b text-center">{{ user.name }}</td>
                    <td class="px-2 py-2 border-b text-center">{{ user.email }}</td>
                    <td class="px-2 py-2 border-b text-center">
                      <button 
                        @click="openEditModal(user)" 
                        class="px-2 py-2 bg-blue-500 text-white border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-800 hover:text-black"
                      >
                        Edit
                      </button>
                      <button 
                        @click="openDeleteModal(user)" 
                        class="btn ms-1 inline-flex items-center px-2 py-2 bg-red-500 border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-red-800 hover:text-black"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              
              <p v-else class="text-center text-gray-700 mt-6">No users found.</p>
              
              <div v-if="users.length > 0" class="flex justify-between items-center mt-4">
                <div class="text-sm text-gray-700">
                  Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} users
                </div>
                <div>
                  <pagination-links 
                    :meta="meta" 
                    :links="links"
                    @page-selected="fetchUsers"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import PaginationLinks from './PaginationLinks.vue';
  
  export default {
    name: 'UserList',
    components: {
      PaginationLinks
    },
    props: {
      initialUsers: {
        type: Array,
        default: () => []
      },
      initialMeta: {
        type: Object,
        default: () => ({})
      },
      initialLinks: {
        type: Object,
        default: () => ({})
      }
    },
    data() {
      return {
        users: this.initialUsers || [],
        meta: this.initialMeta || {
          current_page: 1,
          from: 0,
          to: 0,
          total: 0,
          last_page: 1,
          path: ''
        },
        links: this.initialLinks || {
          first: null,
          last: null,
          prev: null,
          next: null
        },
        loading: false
      };
    },
    mounted() {
      // If no initial data was provided, fetch users
      if (this.users.length === 0) {
        this.fetchUsers(1);
      }
    },
    methods: {
      fetchUsers(page = 1) {
        this.loading = true;
        const useOverlay = true;
      const message = 'Loading users...';
      
      this.$loading.show(message, useOverlay);
        axios.get(`/vue/user?page=${page}`)
          .then(response => {
            this.users = response.data.data.data;
            this.meta = response.data.data.meta;
            this.links = response.data.data.links;
          })
          .catch(error => {
            console.error('Error fetching users:', error);
          })
          .finally(() => {
            this.loading = false;
            this.$loading.hide();
          });
      },
      openCreateModal() {
        // Emit event to parent component to open the modal for create
        this.$emit('open-create-modal');
      },
      openEditModal(user) {
        // Emit event to parent component to open the modal for edit
        this.$emit('open-edit-modal', user);
      },
      openDeleteModal(user) {
        // Emit event to parent component to open the delete modal
        this.$emit('open-delete-modal', user);
      }
    }
  };
  </script>