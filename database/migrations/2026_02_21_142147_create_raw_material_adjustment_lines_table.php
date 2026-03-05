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
        Schema::create('raw_material_adjustment_lines', function (Blueprint $table) {
            $table->id();

            $table->decimal('theoretical_quantity', 15, 3);      // stock al momento del ajuste
            $table->decimal('counted_quantity', 15, 3);     // lo que el usuario contó
            $table->decimal('difference_quantity', 15, 3);  // counted - system

            $table->foreignId('stock_id')
                ->constrained('raw_material_stocks')
                ->restrictOnDelete();

            $table->foreignId('document_id')
                ->constrained('raw_material_documents')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_adjustment_lines');
    }
};
