<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Client::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Address',
            'Created At',
        ];
    }

    /**
     * @param Client $client
     * @return array
     */
    public function map($client): array
    {
        return [
            $client->name,
            $client->email,
            $client->phone ?: 'N/A',
            $client->address ?: 'N/A',
            $client->created_at->format('Y-m-d H:i:s'),
        ];
    }
}



