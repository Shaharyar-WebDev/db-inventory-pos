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
        Schema::create('transfer_between_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number', 255)->unique();
            $table->foreignId('from_account_id')->constrained('accounts', 'id')->restrictOnDelete();
            $table->foreignId('to_account_id')->constrained('accounts', 'id')->restrictOnDelete();
            $table->money('amount');
            $table->text('remarks')->nullable();
            $table->json('attachments')->nullable()->default(null);
            $table->userstamps();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_between_accounts');
    }
};
