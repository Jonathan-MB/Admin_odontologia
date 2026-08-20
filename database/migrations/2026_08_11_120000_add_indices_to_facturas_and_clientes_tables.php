<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Solo agrega indices. No cambia columnas, ni datos, ni comportamiento:
     * las mismas consultas devuelven exactamente las mismas filas.
     */
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {

            // Cierre diario: where('sede_id') + filtro por fecha
            // FacturaController::totalDia
            $table->index(['sede_id', 'created_at'], 'facturas_sede_created_index');

            // Rango de fechas: whereBetween('created_at', [from, to])
            // FacturaController::index
            $table->index('created_at', 'facturas_created_index');

        });

        Schema::table('clientes', function (Blueprint $table) {

            // Agenda del dia: where('sede_id') + fecha + orderBy('fecha_cita')
            // ClienteController::citas
            $table->index(['sede_id', 'fecha_cita'], 'clientes_sede_cita_index');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {

            $table->dropIndex('facturas_sede_created_index');
            $table->dropIndex('facturas_created_index');

        });

        Schema::table('clientes', function (Blueprint $table) {

            $table->dropIndex('clientes_sede_cita_index');

        });
    }
};
