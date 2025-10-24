<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Training Service</h2>
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

        <form method="POST" action="{{ route('master.trainings.update', $training) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700">Service Name</label>
                <input type="text" name="name" value="{{ old('name', $training->name) }}" required class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Description (Optional)</label>
                <textarea name="description" rows="3" class="w-full border rounded p-2">{{ old('description', $training->description) }}</textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                <a href="{{ route('master.trainings.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>