<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog Index') }}
        </h2>
    </x-slot>

    <!-- Vue-managed container -->
    <div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold">Blog List</h2>
                            <!-- Use Vue @click to call the modal's openModal method -->
                            <button @click="$refs.blogModal.openModal()"
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                + Create Blog
                            </button>
                        </div>

                        @if ($response->count() > 0)
                            <table class="min-w-full table-auto border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-2 border-b text-center">ID</th>
                                        <th class="px-2 py-2 border-b text-center">Title</th>
                                        <th class="px-2 py-2 border-b text-center">Content</th>
                                        <th class="px-2 py-2 border-b text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($response as $blog)
                                        <tr>
                                            <td class="px-2 py-2 border-b text-center">{{ $blog->id }}</td>
                                            <td class="px-2 py-2 border-b text-center">{{ $blog->title }}</td>
                                            <td class="px-2 py-2 border-b text-center">{{ $blog->content }}</td>
                                            <td class="px-2 py-2 border-b text-center">
                                                <!-- Use @click with $refs to open the modal in edit mode -->
                                                <button data-blog='@json($blog)'
                                                    @click="$refs.blogModal.openModal(JSON.parse($event.currentTarget.dataset.blog))"
                                                    class="px-2 py-2 bg-blue-500 text-white  border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-800 hover:text-black">
                                                    Edit
                                                </button>
                                             
                                                    <button data-blog='@json($blog)'
                                                    @click="$refs.deleteModal.openDeleteModal(JSON.parse($event.currentTarget.dataset.blog))"
                                                        class="btn ms-1 inline-flex items-center px-2 py-2 bg-red-500 border border-gray-300 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-red-800 hover:text-black">
                                                        Delete
                                                    </button>
                                               
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                             <div class="flex justify-between items-center mt-4">
                            {{-- <div class="text-sm text-gray-700">
                                Showing {{ $response->firstItem() }} to {{ $response->lastItem() }} of
                                {{ $response->total() }} blogs
                            </div> --}}
                            <div>
                                {{ $response->links('vendor.pagination.tailwind') }} <!-- Tailwind pagination -->
                            </div>
                        </div>
                        @else
                            <p class="text-center text-gray-700 mt-6">No blogs found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Vue Blog Modal Component with ref -->
        <blog-modal ref="blogModal"></blog-modal>
        <delete-blog-modal ref="deleteModal"></delete-blog-modal>
        
    </div>
</x-app-layout>
