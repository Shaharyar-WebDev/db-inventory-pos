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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 255)->unique();
            $table->foreignId('sale_id')->constrained()->restrictOnDelete();
            $table->text('description')->nullable();
            $table->money('total');
            $table->discountType();
            $table->discountValue();
            $table->money('grand_total');
            $table->money('discount_amount');
            $table->belongsToOutlet();
            $table->userstamps();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
