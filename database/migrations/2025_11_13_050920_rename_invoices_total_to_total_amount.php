<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists before renaming
        if (Schema::hasColumn('invoices', 'total') && !Schema::hasColumn('invoices', 'total_amount')) {
            DB::statement('ALTER TABLE invoices CHANGE total total_amount DECIMAL(10,2)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if column exists before renaming back
        if (Schema::hasColumn('invoices', 'total_amount') && !Schema::hasColumn('invoices', 'total')) {
            DB::statement('ALTER TABLE invoices CHANGE total_amount total DECIMAL(10,2)');
        }
    }
};
