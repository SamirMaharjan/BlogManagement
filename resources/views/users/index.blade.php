<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Index') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">User List</h2>
                        <button onclick="openUserModal()" 
                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            + Create User
                        </button>
                    </div>

                    <!-- Check if users are available -->
                    @if ($response->count() > 0)
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-2 py-2 border-b">ID</th>
                                    <th class="px-2 py-2 border-b">Name</th>
                                    <th class="px-2 py-2 border-b">Email</th>
                                    <th class="px-2 py-2 border-b">Created At</th>
                                    <th class="px-2 py-2 border-b">Updated At</th>
                                    <th class="px-2 py-2 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($response as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-2 border-b">{{ $user->id }}</td>
                                        <td class="px-2 py-2 border-b">{{ $user->name }}</td>
                                        <td class="px-2 py-2 border-b">{{ $user->email }}</td>
                                        <td class="px-2 py-2 border-b">{{ $user->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-2 py-2 border-b">{{ $user->updated_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-2 py-2 border-b text-center">
                                            <!-- View Button -->
                                            <button
                                                class ="btn ms-1 inline-flex items-center px-2 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-600 hover:text-white">
                                                <a href="{{ route('users.show', $user->id) }}"
                                                    class="px-2 py-2 ">View</a>
                                            </button>
                                            <button onclick="openUserModal({{ json_encode($user) }})"
                                                class ="btn ms-1 inline-flex items-center px-2 py-2 bg-blue-600 border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-green-400 hover:text-black">
                                                <p class="px-2 py-2 ">Edit</a>
                                            </button>
                                            <button onclick="openDeleteModal({{ $user->id }})"
                                                class ="btn ms-1 inline-flex items-center px-2 py-2 bg-red-500 border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-red-800 hover:text-black">
                                                <p class="px-2 py-2 ">Delete</a>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination links -->
                        <div class="flex justify-between items-center mt-4">
                            {{-- <div class="text-sm text-gray-700">
                                Showing {{ $response->firstItem() }} to {{ $response->lastItem() }} of
                                {{ $response->total() }} users
                            </div> --}}
                            <div>
                                {{ $response->links('vendor.pagination.tailwind') }} <!-- Tailwind pagination -->
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-700 mt-6">No users found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-user-deletion"
        class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md w-1/4">
            <form method="post" id="delete-user-form" action="#" class="p-6">
                @csrf
                @method('DELETE')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete this account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete this account.') }}
                </p>

                <!-- Display the userId here -->
                <p class="mt-4 text-sm text-gray-600">
                    <strong>{{ __('User ID:') }}</strong> <span id="user-id"></span>
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button onclick="closeDeleteModal()">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </div>


    <!-- Create/Edit User Modal -->
    <div id="user-modal" class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md w-1/3">
            <h2 id="modal-title" class="text-lg font-medium text-gray-900">Edit User</h2>

            <form method="post" id="user-form" action="#" class="p-6">
                @csrf
                <input type="hidden" id="user-method" name="_method" value="POST">
                <input type="hidden" id="user-id" name="user_id">

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <p id="name-error" class="text-red-500 text-sm hidden">Name is required.</p>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <p id="email-error" class="text-red-500 text-sm hidden">Valid email is required.</p>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <p id="password-error" class="text-red-500 text-sm hidden">Password must be at least 6 characters.
                    </p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <p id="confirm-password-error" class="text-red-500 text-sm hidden">Passwords do not match.</p>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="closeUserModal()"
                        class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md ml-3">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        // Open the modal and set the form action and user ID
        function openDeleteModal(userId) {
            document.getElementById('confirm-user-deletion').classList.remove('hidden');
            document.getElementById('user-id').textContent = userId;
            document.getElementById('delete-user-form').action = "{{ route('users.destroy', ':id') }}".replace(':id',
                userId); // Set the form action URL dynamically
        }

        // Close the modal
        function closeDeleteModal() {
            document.getElementById('confirm-user-deletion').classList.add('hidden');
        }

        function openUserModal(user = null) {
            const modal = document.getElementById('user-modal');
            const form = document.getElementById('user-form');
            const methodInput = document.getElementById('user-method');
            const title = document.getElementById('modal-title');

            if (user) {
                // Edit Mode
                title.textContent = "Edit User";
                document.getElementById('user-id').value = user.id;
                document.getElementById('name').value = user.name;
                document.getElementById('email').value = user.email;
                document.getElementById('password').value = "";
                document.getElementById('confirm-password').value = "";
                form.action = "{{ route('users.update', ':id') }}".replace(':id', user.id);
                methodInput.value = "PUT";
            } else {
                // Create Mode
                title.textContent = "Create User";
                document.getElementById('user-id').value = "";
                document.getElementById('name').value = "";
                document.getElementById('email').value = "";
                document.getElementById('password').value = "";
                document.getElementById('confirm-password').value = "";
                form.action = "{{ route('users.store') }}";
                methodInput.value = "POST";
            }

            modal.classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('user-modal').classList.add('hidden');
        }

        document.getElementById('user-form').addEventListener('submit', function(e) {
            let valid = true;

            // Get form elements
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm-password');
            const userId = document.getElementById('user-id').value; // Check if we are editing

            const nameError = document.getElementById('name-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');

            // Reset errors
            nameError.classList.add('hidden');
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            confirmPasswordError.classList.add('hidden');

            // Validation checks
            if (name.value.trim() === '') {
                nameError.classList.remove('hidden');
                valid = false;
            }

            if (!email.value.match(/^\S+@\S+\.\S+$/)) {
                emailError.classList.remove('hidden');
                valid = false;
            }

            // Password validation: Required for create, optional for edit
            if (!userId && password.value.length < 6) { // If creating, password is required
                passwordError.classList.remove('hidden');
                valid = false;
            }

            if (password.value.length > 0 && password.value.length < 6) {
                passwordError.classList.remove('hidden');
                valid = false;
            }

            if (password.value !== confirmPassword.value) {
                confirmPasswordError.classList.remove('hidden');
                valid = false;
            }

            if (!valid) {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });
    </script>


</x-app-layout>
