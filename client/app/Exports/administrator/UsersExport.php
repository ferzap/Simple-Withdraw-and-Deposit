<?php

namespace App\Exports\administrator;

use App\Models\admin\administrator\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;
    private $limit;
    private $filters;
    private $sorts;

    public function __construct($limit, array $sorts, array $filters)
    {
        $this->limit = $limit;
        $this->sorts = $sorts;
        $this->filters = $filters;
    }

    // ? CHANGE: THE 4TH ARRAY TO SET HEADING
    public function headings(): array
    {
        $date = date('d/m/Y H:i:s');
        return [
            ['Export User'],
            [$date], 
            [],
            [
                'Username',
                'Nama',
                'Email',
                'No.HP',
                'Group',
                'Login Terakhir'
            ]
        ];
    }

    // ? CHANGE: GET DATA FROM MODEL
    public function collection()
    {
        $user = User::with('group')->sort($this->sorts)->filter($this->filters)->paginate($this->limit);
        return $user->except(['user_id']);
    }

    // ? CHANGE:THE DATA WITH DATA FROM COLLECTION
    public function map($user): array
    {
        return [
            $user->username,
            $user->name,
            $user->email,
            $user->mobilephone,
            $user->group->group_title,
            $user->last_login
        ];
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

        $count = User::with('group')->filter($this->filters)->paginate($this->limit)->count();
        $start = 5;
        $sheet->getStyle('2')->getFont()->setBold(true);
        $sheet->getStyle('2')->getFont()->setSize(14);
        $sheet->getStyle('3')->getFont()->setBold(true);
        $sheet->getStyle('3')->getFont()->setSize(11);
        $sheet->mergeCells('B2:G2', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->mergeCells('B3:G3', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->getStyle('5')->getFont()->setBold(true);
        $sheet->getStyle('5')->getFont()->setSize(12);
        $sheet->getStyle("B5:G" . $start + $count . "")->applyFromArray($styleArray);
    }

    public function startCell(): string
    {
        return 'B2';
    }
}
