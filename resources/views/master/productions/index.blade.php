<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manage Production Activities</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('master.productions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Add Production Activity
                </a>
            </div>

            <x-alert />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($productions as $p)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->name }}</td>
                                <td class="px-6 py-4">{{ Str::limit($p->description, 50) }}</td>
                                <td class="px-6 py-4">
                                    @if($p->updater)
                                        {{ $p->updater->name }}
                                        <br><small class="text-gray-500">{{ $p->updated_at?->format('d M Y H:i') }}</small>
                                    @else
                                        <em>System</em>
                                    @endif
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('master.productions.edit', $p) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('master.productions.destroy', $p) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No production activities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">{{ $productions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>