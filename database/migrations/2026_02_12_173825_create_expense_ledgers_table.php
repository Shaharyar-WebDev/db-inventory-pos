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
        Schema::create('expense_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained()->restrictOnDelete();
            $table->money('amount')->default(0);
            $table->morphs('source');
            $table->string('transaction_type', 255)->nullable();
            $table->text('remarks')->nullable();
            $table->belongsToOutlet();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_ledgers');
    }
};
