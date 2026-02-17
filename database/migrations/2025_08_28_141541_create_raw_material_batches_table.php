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
        Schema::create('raw_material_batches', function (Blueprint $table) {
            $table->id();

            $table->string('batch_code', 128)->unique();
            $table->string('external_batch_code', 128)->nullable();

            $table->decimal('received_quantity', 12, 3);
            $table->decimal('received_total_cost', 12, 2);
            $table->decimal('received_unit_cost', 12, 2);

            $table->decimal('current_quantity', 15, 3)->default(0);

            $table->dateTime('received_at');
            $table->date('expiration_date')->nullable();

            $table->foreignId('material_id')
                ->constrained('raw_materials')
                ->restrictOnDelete();

            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_batches');
    }
};
