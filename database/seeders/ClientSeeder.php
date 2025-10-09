<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Client::create([
            'name' => 'John Smith',
            'email' => 'john.smith@email.com',
            'phone' => '+1 (555) 123-4567',
            'address' => '123 Main St, New York, NY 10001'
        ]);

        \App\Models\Client::create([
            'name' => 'ABC Corporation',
            'email' => 'contact@abccorp.com',
            'phone' => '+1 (555) 987-6543',
            'address' => '456 Business Ave, Los Angeles, CA 90210'
        ]);

        \App\Models\Client::create([
            'name' => 'Tech Solutions Ltd',
            'email' => 'info@techsolutions.com',
            'phone' => '+1 (555) 456-7890',
            'address' => '789 Innovation Drive, San Francisco, CA 94105'
        ]);
    }
}
