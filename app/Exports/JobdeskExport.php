<?php

namespace App\Exports;

use App\Models\Jobdesk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JobdeskExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $jobdesks;
    protected $filters;

    public function __construct($jobdesks, $filters = [])
    {
        $this->jobdesks = $jobdesks;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->jobdesks;
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'Instructor',
            'Activity Type',
            'Activity Details',
            'Description',
            'Status',
            'Updated By',
            'Updated At'
        ];
    }

    public function map($jobdesk): array
    {
        // Get activity details
        if ($jobdesk->course) {
            $activity = $jobdesk->course->name . ' (' . ucfirst($jobdesk->course->type) . ')';
        } elseif ($jobdesk->production) {
            $activity = '🏭 ' . $jobdesk->production->name;
        } elseif ($jobdesk->training) {
            $activity = '📚 ' . $jobdesk->training->name;
        } elseif ($jobdesk->internalActivity) {
            $activity = '🏢 ' . $jobdesk->internalActivity->name;
        } else {
            $activity = ucfirst($jobdesk->activity_type);
        }

        return [
            '', // No (filled in styles)
            $jobdesk->activity_date,
            $jobdesk->instructor->name,
            ucfirst($jobdesk->activity_type),
            $activity,
            $jobdesk->description,
            ucfirst($jobdesk->status),
            $jobdesk->updater ? $jobdesk->updater->name : 'System',
            $jobdesk->updated_at->format('d M Y H:i')
        ];
    }

    public function title(): string
    {
        return 'Jobdesk Report';
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
        ]);

        // Auto-number rows
        $sheet->setCellValue('A1', 'No');
        for ($i = 0; $i < count($this->jobdesks); $i++) {
            $sheet->setCellValue('A' . ($i + 2), $i + 1);
        }

        // Auto-fit columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add filter metadata
        if (!empty($this->filters)) {
            $sheet->setCellValue('A' . (count($this->jobdesks) + 3), 'Filters:');
            foreach ($this->filters as $i => $filter) {
                $sheet->setCellValue('A' . (count($this->jobdesks) + 4 + $i), '- ' . $filter);
            }
        }
        $sheet->setCellValue('A' . (count($this->jobdesks) + 6), 'Exported: ' . now()->format('d M Y H:i WIB'));
    }
}