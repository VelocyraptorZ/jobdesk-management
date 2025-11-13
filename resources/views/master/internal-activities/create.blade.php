<x-app-layout>
    <x-slot name="header">Add Internal Activity</x-slot>
    <div class="py-6 max-w-2xl mx-auto">
        <x-alert />
        <form method="POST" action="{{ route('master.internal-activities.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block">Name</label>
                <input type="text" name="name" required class="w-full border rounded p-2" value="{{ old('name') }}">
            </div>
            <div class="mb-4">
                <label>Description (Optional)</label>
                <textarea name="description" class="w-full border rounded p-2">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
            <a href="{{ route('master.internal-activities.index') }}" class="ml-2">Cancel</a>
        </form>
    </div>
</x-app-layout>