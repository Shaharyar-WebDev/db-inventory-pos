<?php

use App\Enums\DiscountType;
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
            $table->enum('discount_type', array_map(fn ($case) => $case->value, DiscountType::cases()));
            $table->decimal('discount_value', 15, 4);
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
