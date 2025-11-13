<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('quotes', 'quote_number')) {
                $table->string('quote_number')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('quotes', 'quote_date')) {
                $table->date('quote_date')->nullable()->after('client_id');
            }
            if (!Schema::hasColumn('quotes', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('quote_date');
            }
            if (!Schema::hasColumn('quotes', 'gst_amount')) {
                $table->decimal('gst_amount', 10, 2)->default(0)->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['quote_number', 'quote_date', 'subtotal', 'gst_amount']);
        });
    }
};
