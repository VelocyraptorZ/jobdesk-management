<x-app-layout>
    <x-slot name="header">Edit Internal Activity</x-slot>
    <div class="py-6 max-w-2xl mx-auto">
        <x-alert />
        <form method="POST" action="{{ route('master.internal-activities.update', $internalActivity) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block">Name</label>
                <input type="text" name="name" required class="w-full border rounded p-2" value="{{ old('name', $internalActivity->name) }}">
            </div>
            <div class="mb-4">
                <label>Description (Optional)</label>
                <textarea name="description" class="w-full border rounded p-2">{{ old('description', $internalActivity->description) }}</textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('master.internal-activities.index') }}" class="ml-2">Cancel</a>
        </form>
    </div>
</x-app-layout>