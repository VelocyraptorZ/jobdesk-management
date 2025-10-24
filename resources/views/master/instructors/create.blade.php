<x-app-layout>
    <x-slot name="header">Add Instructor</x-slot>
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

        <form method="POST" action="{{ route('master.instructors.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label>Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}" required class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label>Field of Expertise</label>
                <input type="text" name="field_of_expertise" value="{{ old('field_of_expertise') }}" required class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label>
                    <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}> Active
                </label>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
            <a href="{{ route('master.instructors.index') }}" class="ml-2">Cancel</a>
        </form>
    </div>
</x-app-layout>