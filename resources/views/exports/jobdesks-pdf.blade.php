<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jobdesk Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; color: #1e3a8a; }
        .filters { background: #f3f4f6; padding: 8px; margin-bottom: 15px; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px 4px; text-align: left; }
        th { background-color: #2563eb; color: white; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; text-align: right; }
        .no { text-align: center; width: 5%; }
        .date { width: 12%; }
        .instructor { width: 15%; }
        .type { width: 12%; }
        .activity { width: 18%; }
        .desc { width: 18%; }
        .status { width: 8%; text-align: center; }
        .updated { width: 12%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INSTRUCTOR JOBDESK REPORT</h1>
        <p>Mechanical Engineering School</p>
    </div>

    @if($filters)
        <div class="filters">
            <strong>Filters Applied:</strong>
            @foreach($filters as $filter)
                <div>- {{ $filter }}</div>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="date">Date</th>
                <th class="instructor">Instructor</th>
                <th class="type">Activity Type</th>
                <th class="activity">Activity Details</th>
                <th class="desc">Description</th>
                <th class="status">Status</th>
                <th class="updated">Updated By</th>
                <th class="updated">Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobdesks as $index => $j)
                <tr>
                    <td class="no">{{ $index + 1 }}</td>
                    <td class="date">{{ $j->activity_date }}</td>
                    <td class="instructor">{{ $j->instructor->name }}</td>
                    <td class="type">{{ ucfirst($j->activity_type) }}</td>
                    <td class="activity">
                        @if($j->course)
                            {{ $j->course->name }} ({{ ucfirst($j->course->type) }})
                        @elseif($j->production)
                            🏭 {{ $j->production->name }}
                        @elseif($j->training)
                            📚 {{ $j->training->name }}
                        @elseif($j->internalActivity)
                            🏢 {{ $j->internalActivity->name }}
                        @else
                            {{ ucfirst($j->activity_type) }}
                        @endif
                    </td>
                    <td class="desc">{{ $j->description }}</td>
                    <td class="status">{{ ucfirst($j->status) }}</td>
                    <td class="updated">{{ $j->updater ? $j->updater->name : 'System' }}</td>
                    <td class="updated">{{ $j->updated_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Exported on: {{ $exportedAt }} (WIB)<br>
        Total Records: {{ $jobdesks->count() }}
    </div>
</body>
</html>