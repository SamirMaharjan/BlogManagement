<template>
    <div>
      <!-- Header slot will be handled by the Laravel layout -->
      
      <!-- Vue-managed container -->
      <user-list ref="UserList"
        @open-create-modal="$refs.userModal.openModal()"
        @open-edit-modal="$refs.userModal.openModal($event)"
        @open-delete-modal="$refs.deleteModal.openDeleteModal($event)"
      ></user-list>
      
      <!-- Modal components -->
      <user-modal ref="userModal" @user-created="refreshUserList"
      @user-updated="refreshUserList"></user-modal>
      <delete-modal ref="deleteModal" @user-deleted="refreshUserList"></delete-modal>
    </div>
  </template>
  
  <script>
  import UserList from './UserList.vue';
  import UserModal from './UserModal.vue';
  import DeleteModal from './DeleteModal.vue';
  
  export default {
    name: 'UserManager',
    components: {
      UserList,
      UserModal,
      DeleteModal
    },
    methods: {
      refreshUserList() {
        // Access the UserList component instance and call its fetchUsers method
        this.$refs.UserList.fetchUsers();
        // For Vue 3, you would use refs instead of $children
      }
    }
  };
  </script>