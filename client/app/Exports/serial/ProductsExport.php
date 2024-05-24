<?php

namespace App\Exports\serial;

use App\Models\admin\serial\Product;
use DateTime;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithColumnFormatting, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;
    private $limit;
    private $sorts;
    private $filters;

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
            ['Export List Serial RO'],
            [$date], 
            [],
            [
                'Serial',
                'Pembeli',
                'Tanggal Pembelian',
                'Status Aktif',
                'Tanggal Aktivasi',
                'Status Digunakan',
                'Pengguna',
                'Tanggal Digunakan',
                'Admin',
                'Expired',
            ]
        ];
    }

    // ? CHANGE: GET DATA FROM MODEL
    public function collection()
    {
        $query = Product::getData($this->sorts, $this->filters);
        $serial = $query->paginate($this->limit);

        $serialData = [];
        $serialData = $serial->map(function ($item) {
            if(!empty($item->buyer)){
                $item->buyer_name = getMid($item->buyer) .' ('. getMemberName($item->buyer) . ')';
                $item->buydate = new DateTime($item->buydate);
            } else {
                $item->buyer_name = '-';
                $item->buydate = '-';
            }
            if(!empty($item->user)){
                $item->user_name = getMid($item->user) .' ('. getMemberName($item->user) . ')';
                $item->usedate = new DateTime($item->usedate);
            } else {
                $item->user_name = '-';
                $item->usedate = '-';            
            }

            !empty($item->admin) || $item->admin !== "" ? $item->admin = $item->admin : $item->admin = '-';
            $item->isactive == "1" ? $item->isactive = 'Aktif' : $item->isactive = 'Tidak Aktif';
            $item->isused == "Y" ? $item->isused = 'Iya' : $item->isused = 'Tidak';

            $item->activedate = new DateTime($item->activedate);            
            $item->expired_date = Datetime::createFromFormat('!Y-m-d', $item->expired_date);          

            return $item;
        });

        return $serialData;
    }

    // ? CHANGE:THE DATA WITH DATA FROM COLLECTION
    public function map($serial): array
    {
        if($serial->buydate != '-'){

        }
        return [
            $serial->serial,
            $serial->buyer_name,
            ($serial->buydate != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->buydate) : $serial->buydate,
            $serial->isactive,
            ($serial->activedate != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->activedate) : $serial->activedate,
            $serial->isused,
            $serial->user_name,
            ($serial->usedate != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->usedate) : $serial->usedate,
            $serial->admin,
            ($serial->expired_date != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->expired_date) : $serial->expired_date,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DATETIME,
            'F' => NumberFormat::FORMAT_DATE_DATETIME,
            'I' => NumberFormat::FORMAT_DATE_DATETIME,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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

        $count = Product::getData($this->sorts, $this->filters)->paginate($this->limit)->count();
        $start = 5;
        $sheet->getStyle('2')->getFont()->setBold(true);
        $sheet->getStyle('2')->getFont()->setSize(14);
        $sheet->getStyle('3')->getFont()->setBold(true);
        $sheet->getStyle('3')->getFont()->setSize(11);
        $sheet->mergeCells('B2:K2', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->mergeCells('B3:K3', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->getStyle('5')->getFont()->setBold(true);
        $sheet->getStyle('5')->getFont()->setSize(12);
        $sheet->getStyle("B5:K" . $start + $count . "")->applyFromArray($styleArray);
    }

    public function startCell(): string
    {
        return 'B2';
    }
}
