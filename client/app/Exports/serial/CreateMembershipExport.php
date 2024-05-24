<?php

namespace App\Exports\serial;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CreateMembershipExport implements WithCustomStartCell, WithHeadings, ShouldAutoSize, WithStyles, FromArray, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;
    protected $data;
    protected $type;
    protected $total;

    public function __construct(array $data, $type, $total)
    {
        $this->data = $data;
        $this->type = $type;
        $this->total = $total;
    }

    // ? CHANGE: THE 4TH ARRAY TO SET HEADING
    public function headings(): array
    {
        $date = date('d/m/Y H:i:s');
        return [
            ['Serial Mitra Usaha ' . $this->type],
            [$date], 
            [],
            [
                'Serial',
                'Pin',
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 35,
            'C' => 20,            
        ];
    }

    public function array(): array
    {
        return $this->data;
    }
    
    public function styles(Worksheet $sheet)
    {
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '2f2f2f'],
                ],
            ],
        ];

        $start = 5;
        $sheet->getStyle('2')->getFont()->setBold(true);
        $sheet->getStyle('2')->getFont()->setSize(14);
        $sheet->getStyle('3')->getFont()->setBold(true);
        $sheet->getStyle('3')->getFont()->setSize(11);
        $sheet->mergeCells('B2:C2', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->mergeCells('B3:C3', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->getStyle('5')->getFont()->setBold(true);
        $sheet->getStyle('5')->getFont()->setSize(12);
        $sheet->getStyle("B5:C" . $start + $this->total . "")->applyFromArray($styleArray);
    }

    public function startCell(): string
    {
        return 'B2';
    }
}
