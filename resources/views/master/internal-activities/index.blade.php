<x-app-layout>
    <x-slot name="header">Manage Internal Activities</x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('master.internal-activities.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Internal Activity</a>
            </div>
            <x-alert />
            <table class="min-w-full bg-white border">
                <thead><tr>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Updated By</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr></thead>
                <tbody>
                    @forelse($activities as $a)
                    <tr>
                        <td class="border px-4 py-2">{{ $a->name }}</td>
                        <td class="border px-4 py-2">{{ Str::limit($a->description, 50) }}</td>
                        <td class="border px-4 py-2">
                            @if($a->updater)
                                {{ $a->updater->name }}
                                <br><small class="text-gray-500">{{ $a->updated_at?->format('d M Y H:i') }}</small>
                            @else
                                <em>System</em>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('master.internal-activities.edit', $a) }}" class="text-blue-500">Edit</a>
                            <form method="POST" action="{{ route('master.internal-activities.destroy', $a) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4">No internal activities.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $activities->links() }}
        </div>
    </div>
</x-app-layout>