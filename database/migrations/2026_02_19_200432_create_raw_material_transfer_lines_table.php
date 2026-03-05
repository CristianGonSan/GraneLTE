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
        Schema::create('raw_material_transfer_lines', function (Blueprint $table) {
            $table->id();

            $table->decimal('quantity', 15, 3)->default(0);

            $table->foreignId('stock_origin_id')
                ->constrained('raw_material_stocks')
                ->restrictOnDelete();

            $table->foreignId('warehouse_dest_id')
                ->constrained('warehouses')
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
        Schema::dropIfExists('raw_material_transfer_lines');
    }
};
