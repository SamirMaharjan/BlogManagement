<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Index') }}
        </h2>
    </x-slot>

    <!-- Vue-managed container -->
    <div id="app">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold">User List</h2>
                            <!-- Use Vue @click to call the modal's openModal method -->
                            <button @click="$refs.userModal.openModal()"
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                + Create User
                            </button>
                        </div>

                        @if ($response->count() > 0)
                            <table class="min-w-full table-auto border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-2 border-b text-center">ID</th>
                                        <th class="px-2 py-2 border-b text-center">Name</th>
                                        <th class="px-2 py-2 border-b text-center">Email</th>
                                        <th class="px-2 py-2 border-b text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($response as $user)
                                        <tr>
                                            <td class="px-2 py-2 border-b text-center">{{ $user->id }}</td>
                                            <td class="px-2 py-2 border-b text-center">{{ $user->name }}</td>
                                            <td class="px-2 py-2 border-b text-center">{{ $user->email }}</td>
                                            <td class="px-2 py-2 border-b text-center">
                                                <!-- Use @click with $refs to open the modal in edit mode -->
                                                <button data-user='@json($user)'
                                                    @click="$refs.userModal.openModal(JSON.parse($event.currentTarget.dataset.user))"
                                                    class="px-2 py-2 bg-blue-500 text-white  border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-800 hover:text-black">
                                                    Edit
                                                </button>
                                             
                                                    <button data-user='@json($user)'
                                                    @click="$refs.deleteModal.openDeleteModal(JSON.parse($event.currentTarget.dataset.user))"
                                                        class="btn ms-1 inline-flex items-center px-2 py-2 bg-red-500 border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-red-800 hover:text-black">
                                                        Delete
                                                    </button>
                                               
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-gray-700 mt-6">No users found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Vue User Modal Component with ref -->
        <user-modal ref="userModal"></user-modal>
        <delete-modal ref="deleteModal"></delete-modal>
        
    </div>
</x-app-layout>
