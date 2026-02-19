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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 255)->unique()->nullable();
            $table->string('thumbnail', 255)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->json('additional_images')->nullable()->default(null);
            $table->json('attachments')->nullable()->default(null);
            $table->foreignId('unit_id')->nullable()->constrained('units', 'id')->restrictOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->restrictOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->restrictOnDelete();
            $table->money('cost_price');
            $table->money('selling_price');
            $table->json('tags')->nullable()->default(null);
            $table->status();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
