<x-app-layout>
    <x-slot name="header">
        Edit Jobdesk Entry
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        <x-alert />
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('jobdesks.entries.update', $entry) }}">
            @csrf
            @method('PUT')

            <!-- Instructor -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Instructor</label>
                <select name="instructor_id" class="w-full border border-gray-300 rounded-md p-2" required>
                    <option value="">-- Select Instructor --</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $entry->instructor_id) == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Activity Date</label>
                <input type="date" name="activity_date" value="{{ old('activity_date', $entry->activity_date ? \Carbon\Carbon::parse($entry->activity_date)->format('Y-m-d') : '') }}" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>

            <!-- Date & Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Time</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $entry->start_time?->format('H:i')) }}" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">End Time (Optional)</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $entry->end_time?->format('H:i')) }}" class="w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>

            <!-- Activity Type -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Activity Type</label>
                <select name="activity_type" id="activityType" class="w-full border border-gray-300 rounded-md p-2" required>
                    <option value="">-- Select Activity --</option>
                    <option value="practical" {{ old('activity_type', $entry->activity_type) == 'practical' ? 'selected' : '' }}>Practical Session</option>
                    <option value="theoretical" {{ old('activity_type', $entry->activity_type) == 'theoretical' ? 'selected' : '' }}>Theoretical Session</option>
                    <option value="production" {{ old('activity_type', $entry->activity_type) == 'production' ? 'selected' : '' }}>Production Activity</option>
                    <option value="training" {{ old('activity_type', $entry->activity_type) == 'training' ? 'selected' : '' }}>Training Service</option>
                    <option value="internal" {{ old('activity_type', $entry->activity_type) == 'internal' ? 'selected' : '' }}>Internal Activity</option>
                </select>
            </div>

            <!-- Dynamic Fields (pre-filled) -->
            <div id="courseField" class="mb-4" style="display: none;">
                <label class="block text-gray-700 font-medium mb-2">Course</label>
                <select name="course_id" class="w-full border border-gray-300 rounded-md p-2">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $entry->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ ucfirst($course->type) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="productionField" class="mb-4" style="display: none;">
                <label class="block text-gray-700 font-medium mb-2">Production Activity</label>
                <select name="production_id" class="w-full border border-gray-300 rounded-md p-2">
                    <option value="">-- Select Production --</option>
                    @foreach($productions as $p)
                        <option value="{{ $p->id }}" {{ old('production_id', $entry->production_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="trainingField" class="mb-4" style="display: none;">
                <label class="block text-gray-700 font-medium mb-2">Training Service</label>
                <select name="training_id" class="w-full border border-gray-300 rounded-md p-2">
                    <option value="">-- Select Training --</option>
                    @foreach($trainings as $t)
                        <option value="{{ $t->id }}" {{ old('training_id', $entry->training_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="internalField" class="mb-4" style="display: none;">
                <label class="block text-gray-700 font-medium mb-2">Internal Activity</label>
                <select name="internal_activity_id" class="w-full border border-gray-300 rounded-md p-2">
                    <option value="">-- Select Internal Activity --</option>
                    @foreach($internalActivities as $ia)
                        <option value="{{ $ia->id }}" {{ old('internal_activity_id', $entry->internal_activity_id) == $ia->id ? 'selected' : '' }}>
                            {{ $ia->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md p-2" required>{{ old('description', $entry->description) }}</textarea>
            </div>

            <!-- Status Info (Read-only) -->
            <div class="mb-4 p-3 bg-blue-50 rounded">
                <label class="block text-gray-700 font-medium mb-1">Current Status</label>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $entry->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($entry->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($entry->status) }}
                </span>
                <p class="text-sm text-gray-500 mt-1">
                    @if($entry->status === 'pending')
                        Awaiting approval from Superadmin.
                    @elseif($entry->status === 'approved')
                        Approved and visible in dashboard.
                    @else
                        Rejected by Superadmin.
                    @endif
                </p>
            </div>

            <!-- Actions -->
            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
                    Update Entry
                </button>
                <a href="{{ route('jobdesks.entries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleFields() {
            const type = document.getElementById('activityType').value;
            const courseField = document.getElementById('courseField');
            const productionField = document.getElementById('productionField');
            const trainingField = document.getElementById('trainingField');
            const internalField = document.getElementById('internalField');

            // Hide all
            courseField.style.display = 'none';
            productionField.style.display = 'none';
            trainingField.style.display = 'none';
            internalField.style.display = 'none';

            // Show relevant
            if (type === 'practical' || type === 'theoretical') {
                courseField.style.display = 'block';
            } else if (type === 'production') {
                productionField.style.display = 'block';
            } else if (type === 'training') {
                trainingField.style.display = 'block';
            } else if (type === 'internal') {
                internalField.style.display = 'block';
            }
        }

        document.getElementById('activityType').addEventListener('change', toggleFields);
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
</x-app-layout>