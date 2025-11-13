<x-app-layout>
    <x-slot name="header">
        Add Jobdesk Entry
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        <x-alert />

        <form method="POST" action="{{ route('jobdesks.entries.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700">Instructor</label>
                <select name="instructor_id" class="w-full border rounded p-2" required>
                    <option value="">-- Select Instructor --</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Activity Date</label>
                <input type="date" name="activity_date" value="{{ old('activity_date') }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Activity Type</label>
                <select name="activity_type" id="activityType" class="w-full border rounded p-2" required>
                    <option value="">-- Select --</option>
                    <option value="practical" {{ old('activity_type') == 'practical' ? 'selected' : '' }}>Practical Session</option>
                    <option value="theoretical" {{ old('activity_type') == 'theoretical' ? 'selected' : '' }}>Theoretical Session</option>
                    <option value="production" {{ old('activity_type') == 'production' ? 'selected' : '' }}>Production Activity</option>
                    <option value="training" {{ old('activity_type') == 'training' ? 'selected' : '' }}>Training Service</option>
                    <option value="internal" {{ old('activity_type', $entry->activity_type ?? '') == 'internal' ? 'selected' : '' }}>Internal Activity</option>
                </select>
            </div>

            <div class="mb-4" id="courseField" style="display: none;">
                <label class="block text-gray-700">Course</label>
                <select name="course_id" class="w-full border rounded p-2">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ ucfirst($course->type) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4" id="productionField" style="display: none;">
                <label class="block text-gray-700">Production Activity</label>
                <select name="production_id" class="w-full border rounded p-2">
                    <option value="">-- Select Production --</option>
                    @foreach($productions as $p)
                        <option value="{{ $p->id }}" {{ old('production_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4" id="trainingField" style="display: none;">
                <label class="block text-gray-700">Training Service</label>
                <select name="training_id" class="w-full border rounded p-2">
                    <option value="">-- Select Training --</option>
                    @foreach($trainings as $t)
                        <option value="{{ $t->id }}" {{ old('training_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="internalField" class="mb-4" style="display: none;">
                <label class="block text-gray-700">Internal Activity</label>
                <select name="internal_activity_id" class="w-full border rounded p-2">
                    <option value="">-- Select Internal Activity --</option>
                    @foreach($internalActivities as $ia)
                        <option value="{{ $ia->id }}" {{ old('internal_activity_id', $entry->internal_activity_id ?? '') == $ia->id ? 'selected' : '' }}>
                            {{ $ia->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded p-2" required>{{ old('description') }}</textarea>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Save Entry</button>
                <a href="{{ route('jobdesks.entries.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>

    
    <script>
        document.getElementById('activityType').addEventListener('change', function () {
            const type = this.value;
            document.getElementById('courseField').style.display = (type === 'practical' || type === 'theoretical') ? 'block' : 'none';
            document.getElementById('productionField').style.display = (type === 'production') ? 'block' : 'none';
            document.getElementById('trainingField').style.display = (type === 'training') ? 'block' : 'none';
            document.getElementById('internalField').style.display = (type === 'internal') ? 'block' : 'none'; // ← add this
        });
    </script>
</x-app-layout>