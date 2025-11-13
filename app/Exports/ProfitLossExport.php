<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitLossExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Calculate revenue from invoices
        $revenue = Invoice::sum('total_amount');
        
        // Calculate expenses
        $expenses = Expense::sum('amount');
        
        // Calculate net profit
        $netProfit = $revenue - $expenses;
        
        return [
            [
                'Revenue',
                number_format($revenue, 2)
            ],
            [
                'Expenses',
                number_format($expenses, 2)
            ],
            [
                'Net Profit',
                number_format($netProfit, 2)
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Item',
            'Amount ($)'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E5E7EB'
                    ]
                ]
            ],
            // Style the Net Profit row
            4 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'FEF3C7'
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
        ];
    }
}



