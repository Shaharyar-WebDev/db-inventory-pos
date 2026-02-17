<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number', 255)->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->text('description')->nullable();
            $table->money('total');
            $table->discountType();
            $table->discountValue();
            $table->money('discount_amount');
            $table->money('grand_total');
            $table->belongsToOutlet();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
