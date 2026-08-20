<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * La columna es nullable a proposito: las facturas que ya existen en
     * produccion quedan con metodo_pago_id = null y no se modifican.
     * En el cierre diario esas facturas aparecen como "Sin registrar".
     */
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {

            $table->foreignId('metodo_pago_id')
                ->nullable()
                ->after('especialista_id')
                ->constrained('metodo_pagos')
                ->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {

            $table->dropForeign(['metodo_pago_id']);
            $table->dropColumn('metodo_pago_id');

        });
    }
};
