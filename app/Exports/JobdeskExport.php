<?php

namespace App\Exports;

use App\Models\Jobdesk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobdeskExport implements FromCollection, WithHeadings
{
    protected $jobdesks;

    public function __construct($jobdesks)
    {
        $this->jobdesks = $jobdesks;
    }

    public function collection()
    {
        return $this->jobdesks->map(function ($j) {
            return [
                'Date' => $j->activity_date,
                'Instructor' => $j->instructor->name,
                'Type' => ucfirst($j->activity_type),
                'Activity' => $j->course ? $j->course->name : ($j->production ? $j->production->name : ($j->training ? $j->training->name : 'N/A')),
                'Description' => $j->description,
                'Start Time' => $j->start_time,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Instructor', 'Type', 'Activity', 'Description', 'Start Time'];
    }
}