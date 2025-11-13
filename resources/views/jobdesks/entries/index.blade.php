<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Jobdesk Entries</h2>
    </x-slot>

    <div class="py-6">
        <!-- Filters -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Entries</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instructor</label>
                        <select name="instructor_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">All Instructors</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ request('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activity Type</label>
                        <select name="activity_type" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">All Types</option>
                            <option value="practical" {{ request('activity_type') == 'practical' ? 'selected' : '' }}>Practical</option>
                            <option value="theoretical" {{ request('activity_type') == 'theoretical' ? 'selected' : '' }}>Theoretical</option>
                            <option value="production" {{ request('activity_type') == 'production' ? 'selected' : '' }}>Production</option>
                            <option value="training" {{ request('activity_type') == 'training' ? 'selected' : '' }}>Training</option>
                            <option value="internal" {{ request('activity_type') == 'internal' ? 'selected' : '' }}>Internal Activity</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="lg:col-span-5 flex space-x-3 pt-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Apply Filters
                        </button>
                        <a href="{{ route('jobdesks.entries.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Entries Table -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 flex justify-between items-center border-b">
                    <h3 class="text-lg font-medium text-gray-900">Entries List</h3>
                    <a href="{{ route('jobdesks.entries.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Add Entry
                    </a>
                </div>

                <x-alert />

                @if($jobdesks->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        No jobdesk entries found.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated By</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($jobdesks as $j)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $j->activity_date }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $j->instructor->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ ucfirst($j->activity_type) }}</td>
                                    <td class="px-4 py-3">
                                        @if($j->course)
                                            {{ $j->course->name }} ({{ ucfirst($j->course->type) }})
                                        @elseif($j->production)
                                            🏭 {{ $j->production->name }}
                                        @elseif($j->training)
                                            📚 {{ $j->training->name }}
                                        @elseif($j->internalActivity)
                                            ⚙️ {{ $j->internalActivity->name }}
                                        @else
                                            <em>{{ ucfirst($j->activity_type) }}</em>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($j->status === 'pending')
                                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                        @elseif($j->status === 'approved')
                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Approved</span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ Str::limit($j->description, 50) }}</td>
                                    <td class="px-4 py-3">
                                        @if($j->updater)
                                            {{ $j->updater->name }}
                                            <br><small class="text-gray-500">{{ $j->updated_at?->format('d M Y H:i') }}</small>
                                        @else
                                            <em>System</em>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 space-x-2">
                                        <a href="{{ route('jobdesks.entries.edit', $j) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                        <form method="POST" action="{{ route('jobdesks.entries.destroy', $j) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Delete this entry?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t">
                        {{ $jobdesks->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>