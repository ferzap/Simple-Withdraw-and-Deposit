<?php

namespace App\Exports\member;

use App\Models\admin\member\Member;
use App\Models\admin\member\Network;
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

class MembersExport implements FromCollection, WithColumnFormatting, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
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
            ['Export Member'],
            [$date], 
            [],
            [
                'Member ID',
                'Nama Member',
                'Sponsor',
                'Upline',
                'No. Identitas',
                'Bank',
                'No. Rekening',
                'Kota',
                'Provinsi',
                'No. HP',
                'Serial',
                'PIN',
                'Tanggal Gabung',
            ]
        ];
    }

    // ? CHANGE: GET DATA FROM MODEL
    public function collection()
    {
        $query = Member::getData($this->sorts, $this->filters);
        $member = $query->paginate($this->limit);

        $memberData = [];
        $memberData = $member->map(function ($item) {
            $item->member_sponsor = '-';
            $item->member_upline = '-';
            $item->member_province_name = '-';
            $item->member_city_name = '-';
            if ($item->network_sponsor_network_id) {
                $item->member_sponsor = Network::find($item->network_sponsor_network_id)->value('network_mid') . "-" . Member::find($item->network_sponsor_network_id)->value('member_name');
            }
            if ($item->network_upline_network_id) {
                $item->member_upline = Network::find($item->network_upline_network_id)->value('network_mid') . "-" . Member::find($item->network_upline_network_id)->value('member_name');
            }
            if ($item->member_province) $item->member_province_name = areaName($item->member_province);
            if ($item->member_city) $item->member_city_name = areaName($item->member_city);

            $item->member_join_datetime = new DateTime($item->member_join_datetime);

            return $item;
        });

        return $memberData;
    }

    // ? CHANGE:THE DATA WITH DATA FROM COLLECTION
    public function map($member): array
    {
        return [
            $member->network_mid,
            $member->member_name,
            $member->member_sponsor,
            $member->member_upline,
            $member->member_identity_no,
            $member->member_bank_name,
            $member->member_bank_account_no,
            $member->member_city_name,
            $member->member_province_name,
            $member->member_mobilephone,
            $member->member_serial_id,
            $member->member_serial_pin,
            \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($member->member_join_datetime),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER,
            'N' => NumberFormat::FORMAT_DATE_DATETIME,
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

        $count = Member::getData($this->sorts, $this->filters)->paginate($this->limit)->count();
        $start = 5;
        $sheet->getStyle('2')->getFont()->setBold(true);
        $sheet->getStyle('2')->getFont()->setSize(14);
        $sheet->getStyle('3')->getFont()->setBold(true);
        $sheet->getStyle('3')->getFont()->setSize(11);
        $sheet->mergeCells('B2:N2', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->mergeCells('B3:N3', Worksheet::MERGE_CELL_CONTENT_MERGE);
        $sheet->getStyle('5')->getFont()->setBold(true);
        $sheet->getStyle('5')->getFont()->setSize(12);
        $sheet->getStyle("B5:N" . $start + $count . "")->applyFromArray($styleArray);
    }

    public function startCell(): string
    {
        return 'B2';
    }
}
