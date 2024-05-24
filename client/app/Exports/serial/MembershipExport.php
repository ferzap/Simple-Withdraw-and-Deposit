<?php

namespace App\Exports\serial;

use App\Models\admin\serial\Membership;
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

class MembershipExport implements FromCollection, WithColumnFormatting, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
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
            ['Export List Serial Mitra Usaha'],
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
        $query = Membership::getData($this->sorts, $this->filters);
        $serial = $query->paginate($this->limit);

        $serialData = [];
        $serialData = $serial->map(function ($item) {
            if (!empty($item->serial_buyer_network_id)) {
                $item->buyer_name = getMid($item->serial_buyer_network_id) .' ('. getMemberName($item->serial_buyer_network_id) . ')';
                $item->serial_buy_datetime = new DateTime($item->serial_buy_datetime);
            } else {
                $item->buyer_name = '-';
                $item->serial_buy_datetime = '-';
            }
            if (!empty($item->serial_user_network_id)) {
                $item->user_name = getMid($item->serial_user_network_id) .' ('. getMemberName($item->serial_user_network_id) . ')';
                $item->serial_use_datetime = new DateTime($item->serial_use_datetime);
            } else {
                $item->user_name = '-';
                $item->serial_use_datetime = '-';
            }

            if (!empty($item->serial_activedate)) {
                $item->serial_activedate = new DateTime($item->serial_activedate);
            } else {
                $item->serial_activedate = '-';
            }

            !empty($item->serial_admin) || $item->serial_admin !== "" ? $item->serial_admin = $item->serial_admin : $item->serial_admin = '-';
            $item->serial_is_active == "1" ? $item->serial_is_active = 'Aktif' : $item->serial_is_active = 'Tidak Aktif';
            $item->serial_is_used == "Y" ? $item->serial_is_used = 'Iya' : $item->serial_is_used = 'Tidak';

            $item->serial_expired_date !== '0000-00-00' ? $item->serial_expired_date = Datetime::createFromFormat('!Y-m-d', $item->serial_expired_date) : $item->serial_expired_date = '-';   

            return $item;
        });

        return $serialData;
    }

    // ? CHANGE:THE DATA WITH DATA FROM COLLECTION
    public function map($serial): array
    {
        if($serial->serial_buy_datetime != '-'){

        }
        return [
            $serial->serial_code,
            $serial->buyer_name,
            ($serial->serial_buy_datetime != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->serial_buy_datetime) : $serial->serial_buy_datetime,
            $serial->serial_is_active,
            ($serial->serial_activedate != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->serial_activedate) : $serial->serial_activedate,
            $serial->serial_is_used,
            $serial->user_name,
            ($serial->serial_use_datetime != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->serial_use_datetime) : $serial->serial_use_datetime,
            $serial->serial_admin,
            ($serial->serial_expired_date != '-') ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($serial->serial_expired_date) : $serial->serial_expired_date,
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

        $count = Membership::getData($this->sorts, $this->filters)->paginate($this->limit)->count();
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
