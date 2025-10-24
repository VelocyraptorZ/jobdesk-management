<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manage Instructors</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('master.instructors.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Instructor</a>
            </div>
            <x-alert />
            <table class="min-w-full bg-white border">
                <thead><tr>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Expertise</th>
                    <th class="border px-4 py-2">Active</th>
                    <th class="border px-4 py-2">Updated By</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr></thead>
                <tbody>
                    @foreach($instructors as $i)
                    <tr>
                        <td class="border px-4 py-2">{{ $i->name }}</td>
                        <td class="border px-4 py-2">{{ $i->employee_id }}</td>
                        <td class="border px-4 py-2">{{ $i->field_of_expertise }}</td>
                        <td class="border px-4 py-2">{{ $i->is_active ? 'Yes' : 'No' }}</td>
                        <td class="border px-4 py-2">
                            @if($i->updater)
                                {{ $i->updater->name }}
                                <br><small class="text-gray-500">{{ $i->updated_at?->format('d M Y H:i') }}</small>
                            @else
                                <em>System</em>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('master.instructors.edit', $i) }}" class="text-blue-500">Edit</a>
                            <form method="POST" action="{{ route('master.instructors.destroy', $i) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pt-4">
                {{ $instructors->links() }}
            </div>
        </div>
    </div>
</x-app-layout>