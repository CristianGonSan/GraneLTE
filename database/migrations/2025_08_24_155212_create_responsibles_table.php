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
        Schema::create('responsibles', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('identifier', 128)->nullable(); // DNI, código interno, etc.
            $table->string('position', 128)->nullable();   // cargo o rol operativo
            $table->string('department', 128)->nullable();

            $table->string('phone', 20)->nullable();
            $table->string('email', 191)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsibles');
    }
};
