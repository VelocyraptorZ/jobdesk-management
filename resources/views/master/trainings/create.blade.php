<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Add Training Service</h2>
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

        <form method="POST" action="{{ route('master.trainings.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Training Service Name</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., CNC Operator Training"
                >
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Description (Optional)</label>
                <textarea 
                    name="description" 
                    rows="4" 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Brief description of the training service...">{{ old('description') }}</textarea>
            </div>

            <div class="flex space-x-3">
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Save Training Service
                </button>
                <a 
                    href="{{ route('master.trainings.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>