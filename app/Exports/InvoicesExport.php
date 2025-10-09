<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Invoice::with('client')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Invoice Number',
            'Client Name',
            'Invoice Date',
            'Subtotal (excl. GST)',
            'GST Amount',
            'Total',
            'Status',
            'Created At',
        ];
    }

    /**
     * @param Invoice $invoice
     * @return array
     */
    public function map($invoice): array
    {
        return [
            $invoice->invoice_number ?? $invoice->id,
            $invoice->client->name,
            $invoice->invoice_date->format('Y-m-d'),
            number_format($invoice->subtotal, 2),
            number_format($invoice->gst_amount, 2),
            number_format($invoice->total, 2),
            ucfirst($invoice->status),
            $invoice->created_at->format('Y-m-d H:i:s'),
        ];
    }
}



