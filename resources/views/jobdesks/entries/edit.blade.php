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

            <div class="mb-4">
                <label class="block text-gray-700">Instructor</label>
                <select name="instructor_id" class="w-full border rounded p-2" required>
                    <option value="">-- Select Instructor --</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $entry->instructor_id) == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Activity Date</label>
                <input type="date" name="activity_date" value="{{ old('activity_date', $entry->activity_date) }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time', $entry->start_time) }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Activity Type</label>
                <select name="activity_type" id="activityType" class="w-full border rounded p-2" required>
                    <option value="">-- Select --</option>
                    <option value="practical" {{ old('activity_type', $entry->activity_type) == 'practical' ? 'selected' : '' }}>Practical Session</option>
                    <option value="theoretical" {{ old('activity_type', $entry->activity_type) == 'theoretical' ? 'selected' : '' }}>Theoretical Session</option>
                    <option value="production" {{ old('activity_type', $entry->activity_type) == 'production' ? 'selected' : '' }}>Production Activity</option>
                    <option value="training" {{ old('activity_type', $entry->activity_type) == 'training' ? 'selected' : '' }}>Training Service</option>
                </select>
            </div>

            <div class="mb-4" id="courseField" style="display: none;">
                <label class="block text-gray-700">Course</label>
                <select name="course_id" class="w-full border rounded p-2">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $entry->course_id) == $course->id ? 'selected' : '' }}>
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
                        <option value="{{ $p->id }}" {{ old('production_id', $entry->production_id) == $p->id ? 'selected' : '' }}>
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
                        <option value="{{ $t->id }}" {{ old('training_id', $entry->training_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded p-2" required>{{ old('description', $entry->description) }}</textarea>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Update Entry</button>
                <a href="{{ route('jobdesks.entries.index') }}" class="bg-gray-500 text-black px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('activityType').addEventListener('change', function () {
            const type = this.value;
            document.getElementById('courseField').style.display = (type === 'practical' || type === 'theoretical') ? 'block' : 'none';
            document.getElementById('productionField').style.display = (type === 'production') ? 'block' : 'none';
            document.getElementById('trainingField').style.display = (type === 'training') ? 'block' : 'none';
        });

        // Trigger on page load for edit
        document.addEventListener('DOMContentLoaded', function () {
            const type = document.getElementById('activityType').value;
            if (type) {
                document.getElementById('activityType').dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>