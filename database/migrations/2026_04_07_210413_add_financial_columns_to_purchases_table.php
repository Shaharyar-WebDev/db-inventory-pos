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
        Schema::table('purchases', function (Blueprint $table) {
            $table->money('total')->after('description');
            $table->money('tax_charges')->after('grand_total');
            $table->money('delivery_charges')->after('grand_total');
            $table->money('discount_amount')->after('grand_total');
            $table->discountValue()->after('grand_total');
            $table->discountType()->after('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
        });
    }
};
