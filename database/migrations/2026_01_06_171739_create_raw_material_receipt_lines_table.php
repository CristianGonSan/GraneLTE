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
        Schema::create('raw_material_receipt_lines', function (Blueprint $table) {
            $table->id();

            $table->string('external_batch_code', 128)->nullable(); // lote del proveedor (código externo)

            $table->decimal('received_quantity', 12, 3);  // cantidad ingresada (mejor que "original_quantity")
            $table->decimal('received_total_cost', 12, 2); // costo unitario real de ingreso
            $table->decimal('received_unit_cost', 15, 2); // opcional: quantity × unit_cost

            $table->date('expiration_date')->nullable();

            $table->foreignId('material_id')
                ->constrained('raw_materials')
                ->restrictOnDelete();

            $table->foreignId('warehouse_id')
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
        Schema::dropIfExists('raw_material_receipt_lines');
    }
};
