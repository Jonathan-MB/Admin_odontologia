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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->decimal('abono', 10, 2)->default(0);
            $table->decimal('saldo', 10, 2)->default(0);
            $table->string('nombre',45);
            $table->integer('no_factura')->default(0);


            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('especialista_id')->constrained('especialistas')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
