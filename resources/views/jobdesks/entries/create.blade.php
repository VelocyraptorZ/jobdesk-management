<x-app-layout>
    <x-slot name="header">
        Add Jobdesk Entry (Single or Range)
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

        <form method="POST" action="{{ route('jobdesks.entries.store') }}">
            @csrf

            <!-- Instructor -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Instructor</label>
                <select name="instructor_id" class="w-full border border-gray-300 rounded-md p-2" required>
                    <option value="">-- Select Instructor --</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', old('start_date')) }}" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
            </div>

            <!-- Time Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Start Time</label>
                    <input type="time" name="start_time" value="{{ old('start_time', '08:00') }}" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">End Time (Optional)</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full border border-gray-300 rounded-md p-2">
                    <p class="text-xs text-gray-500 mt-1">Leave blank for no duration</p>
                </div>
            </div>

            <!-- Activity Type -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Activity Type</label>
                <select name="activity_type" id="activityType" class="w-full border border-gray-300 rounded-md p-2" required>
                    <option value="">-- Select Activity --</option>
                    <option value="practical" {{ old('activity_type') == 'practical' ? 'selected' : '' }}>Practical Session</option>
                    <option value="theoretical" {{ old('activity_type') == 'theoretical' ? 'selected' : '' }}>Theoretical Session</option>
                    <option value="production" {{ old('activity_type') == 'production' ? 'selected' : '' }}>Production Activity</option>
                    <option value="training" {{ old('activity_type') == 'training' ? 'selected' : '' }}>Training Service</option>
                    <option value="internal" {{ old('activity_type') == 'internal' ? 'selected' : '' }}>Internal Activity</option>
                </select>
            </div>

            <!-- Dynamic Fields -->
            <div id="courseField" class="mb-4" style="display: none;">
                <label class="block text-gray-700 font-medium mb-2">Course</label>
                <select name="course_id" class="w-full border border-gray-300 rounded-md p-2">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                        <option value="{{ $p->id }}" {{ old('production_id') == $p->id ? 'selected' : '' }}>
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
                        <option value="{{ $t->id }}" {{ old('training_id') == $t->id ? 'selected' : '' }}>
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
                        <option value="{{ $ia->id }}" {{ old('internal_activity_id') == $ia->id ? 'selected' : '' }}>
                            {{ $ia->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md p-2" required>{{ old('description') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
                    Create Entries
                </button>
                <a href="{{ route('jobdesks.entries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('activityType').addEventListener('change', function () {
            const type = this.value;
            document.getElementById('courseField').style.display = (type === 'practical' || type === 'theoretical') ? 'block' : 'none';
            document.getElementById('productionField').style.display = (type === 'production') ? 'block' : 'none';
            document.getElementById('trainingField').style.display = (type === 'training') ? 'block' : 'none';
            document.getElementById('internalField').style.display = (type === 'internal') ? 'block' : 'none';
        });

        // Trigger on load
        document.addEventListener('DOMContentLoaded', function () {
            const type = document.getElementById('activityType').value;
            if (type) {
                document.getElementById('activityType').dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>