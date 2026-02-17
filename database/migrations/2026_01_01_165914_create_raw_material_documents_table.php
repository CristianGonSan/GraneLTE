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
        Schema::create('raw_material_documents', function (Blueprint $table) {
            $table->id();

            $table->string('type', 16)->index(); //Enum de PHP
            $table->string('status', 16)->index(); //Enum de PHP

            $table->datetime('effective_at'); //Fecha del evento fisico
            $table->text('description')->nullable();

            $table->string('reference_type', 32)->nullable()->index(); // tipo de documento externo (factura, remisión, guía, etc.)
            $table->string('reference_number', 128)->nullable(); // número de factura/remisión/etc.

            $table->decimal('total_cost', 15, 2)->nullable();

            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('responsibles')
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->datetime('validated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'raw_material_documents');
    }
};
