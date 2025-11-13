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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('fax_number')->nullable()->after('phone');
            $table->string('mobile_number')->nullable()->after('fax_number');
            $table->string('web_address')->nullable()->after('email');
            $table->string('customer_code')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['fax_number', 'mobile_number', 'web_address', 'customer_code']);
        });
    }
};
