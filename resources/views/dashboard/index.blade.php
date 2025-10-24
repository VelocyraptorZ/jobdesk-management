<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Monitoring Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <!-- Filters -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start" value="{{ request('start') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end" value="{{ request('end') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instructor</label>
                        <select name="instructor_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">All Instructors</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ request('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activity Type</label>
                        <select name="activity_type" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                            <option value="">All Types</option>
                            <option value="practical" {{ request('activity_type') == 'practical' ? 'selected' : '' }}>Practical</option>
                            <option value="theoretical" {{ request('activity_type') == 'theoretical' ? 'selected' : '' }}>Theoretical</option>
                            <option value="production" {{ request('activity_type') == 'production' ? 'selected' : '' }}>Production</option>
                            <option value="training" {{ request('activity_type') == 'training' ? 'selected' : '' }}>Training</option>
                        </select>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Filter
                        </button>
                        <div class="flex space-x-2">
                            <a href="{{ route('dashboard.export', array_merge(['format' => 'excel'], request()->all())) }}"
                               class="bg-green-600 text-white text-center px-3 py-2 text-sm rounded hover:bg-green-700 flex-1">
                                Excel
                            </a>
                            <a href="{{ route('dashboard.export', array_merge(['format' => 'pdf'], request()->all())) }}"
                               class="bg-red-600 text-white text-center px-3 py-2 text-sm rounded hover:bg-red-700 flex-1">
                                PDF
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Distribution (Filtered Period)</h3>
                <div id="activityChart" class="h-64"></div>
            </div>
        </div>

        <!-- Jobdesk Table -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Instructor Activities
                        <span class="text-sm text-gray-500 ml-2">
                            ({{ $jobdesks->total() }} records)
                        </span>
                    </h3>

                    @if($jobdesks->isEmpty())
                        <p class="text-gray-500">No jobdesk entries found for the selected filters.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jobdesks as $jobdesk)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($jobdesk->activity_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $jobdesk->instructor->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ ucfirst($jobdesk->activity_type) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($jobdesk->course)
                                                    {{ $jobdesk->course->name }} ({{ ucfirst($jobdesk->course->type) }})
                                                @elseif($jobdesk->production)
                                                    🏭 {{ $jobdesk->production->name }}
                                                @elseif($jobdesk->training)
                                                    📚 {{ $jobdesk->training->name }}
                                                @else
                                                    <em>N/A</em>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ Str::limit($jobdesk->description, 60) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $jobdesks->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        #activityChart {
            display: block !important;
            height: 100% !important;
            width: 100% !important;
        }
    </style>

    <!-- Chart.js - Fixed URL (no spaces!) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const options = {
                chart: {
                    type: 'bar',
                    height: '100%',
                    parentHeightOffset: 0,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                series: [{
                    name: 'Number of Activities',
                    data: {!! json_encode($chartData, JSON_NUMERIC_CHECK) !!}
                }],
                xaxis: {
                    categories: ['Practical', 'Theoretical', 'Production', 'Training'],
                    title: { text: 'Activity Type' }
                },
                yaxis: {
                    title: { text: 'Count' },
                    min: 0,
                    decimalsInFloat: 0
                },
                fill: {
                    opacity: 1
                },
                colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                tooltip: {
                    y: { formatter: function (val) { return val + " activities" } }
                }
            };

            const chart = new ApexCharts(document.querySelector("#activityChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>