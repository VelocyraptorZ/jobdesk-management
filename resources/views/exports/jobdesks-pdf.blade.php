<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jobdesk Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Instructor Jobdesk Report</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Instructor</th>
                <th>Type</th>
                <th>Activity</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobdesks as $j)
            <tr>
                <td>{{ $j->activity_date }}</td>
                <td>{{ $j->instructor->name }}</td>
                <td>{{ ucfirst($j->activity_type) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    @if($j->course)
                        {{ $j->course->name }} ({{ ucfirst($j->course->type) }})
                    @elseif($j->production)
                        🏭 {{ $j->production->name }}
                    @elseif($j->training)
                        📚 {{ $j->training->name }}
                    @else
                        <em>N/A</em>
                    @endif
                </td>
                <td>{{ $j->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>