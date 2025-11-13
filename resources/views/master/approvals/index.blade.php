<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    📋 Pending Jobdesk Approvals
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Review and approve instructor activity entries submitted by Admin (K.UPT)
                </p>
            </div>
            <div class="mt-3 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingEntries->total() }} pending {{ Str::plural('entry', $pendingEntries->total()) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($pendingEntries->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-dashed border-gray-300 p-12 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">All entries approved!</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        There are no pending jobdesk entries requiring your approval at this time.
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pendingEntries as $entry)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <!-- Left: Entry Info -->
                                    <div class="flex-1">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    {{ $entry->instructor->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <span class="font-mono">{{ \Carbon\Carbon::parse($entry->activity_date)->format('d M Y') }}</span>
                                                    •
                                                    @if($entry->course)
                                                        📘 {{ $entry->course->name }} ({{ ucfirst($entry->course->type) }})
                                                    @elseif($entry->production)
                                                        🏭 {{ $entry->production->name }}
                                                    @elseif($entry->training)
                                                        📚 {{ $entry->training->name }}
                                                    @elseif($entry->internalActivity)
                                                        🏢 {{ $entry->internalActivity->name }}
                                                    @else
                                                        {{ ucfirst($entry->activity_type) }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-gray-700 bg-gray-50 p-3 rounded-md">
                                            <p class="text-sm italic">“{{ $entry->description }}”</p>
                                        </div>
                                    </div>

                                    <!-- Right: Actions -->
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <form method="POST" action="{{ route('master.approvals.approve', $entry) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('master.approvals.reject', $entry) }}" 
                                              onsubmit="return confirm('Are you sure you want to reject this entry? This cannot be undone.')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $pendingEntries->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>