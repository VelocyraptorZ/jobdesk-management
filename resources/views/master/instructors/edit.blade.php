<x-app-layout>
    <x-slot name="header">Edit Instructor</x-slot>
    <div class="py-6 max-w-2xl mx-auto">
        <x-alert />
        <form method="POST" action="{{ route('master.instructors.update', $instructor->id) }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block">Name</label>
                <input type="text" name="name" required class="w-full border rounded p-2"
                       value="{{ old('name', $instructor->name) }}">
            </div>

            <div class="mb-4">
                <label>Employee ID</label>
                <input type="text" name="employee_id" required class="w-full border rounded p-2"
                       value="{{ old('employee_id', $instructor->employee_id) }}">
            </div>

            <div class="mb-4">
                <label>Field of Expertise</label>
                <input type="text" name="field_of_expertise" required class="w-full border rounded p-2"
                       value="{{ old('field_of_expertise', $instructor->field_of_expertise) }}">
            </div>

            <div class="mb-4">
                <input type="hidden" name="is_active" value="0">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $instructor->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded">Update</button>
            <a href="{{ route('master.instructors.index') }}" class="ml-2">Cancel</a>
        </form>
    </div>
</x-app-layout>