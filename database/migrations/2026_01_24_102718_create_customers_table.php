<?php

use Illuminate\Support\Facades\Schema;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->foreignId('city_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('area_id')->nullable()->constrained()->restrictOnDelete();
            $table->text('address')->nullable();
            $table->string('contact', 255)->nullable();
            $table->money('opening_balance');
            $table->json('attachments')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
