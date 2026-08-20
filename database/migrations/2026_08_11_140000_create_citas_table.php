<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Crea la tabla y la siembra con lo que ya existe en clientes.fecha_cita.
     * Es una COPIA: la columna original no se modifica ni se borra, y todo lo
     * que hoy la usa (facturacion, historias, impresion) sigue igual.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->string('estado',20)->default('agendada');
            $table->string('observacion',255)->nullable();

            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->foreignId('especialista_id')->nullable()->constrained('especialistas')->nullOnDelete();

            $table->timestamps();

            $table->index(['sede_id', 'fecha_hora'], 'citas_sede_fecha_index');
            $table->index(['especialista_id', 'fecha_hora'], 'citas_especialista_fecha_index');
        });

        // Sembrar con las citas que ya existen.
        // Quedan sin especialista porque ese dato nunca se guardo.
        DB::table('clientes')
            ->whereNotNull('fecha_cita')
            ->orderBy('id')
            ->chunk(200, function ($clientes) {

                $filas = [];

                foreach ($clientes as $cliente) {
                    $filas[] = [
                        'cliente_id'      => $cliente->id,
                        'sede_id'         => $cliente->sede_id,
                        'especialista_id' => null,
                        'fecha_hora'      => $cliente->fecha_cita,
                        'estado'          => 'agendada',
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }

                DB::table('citas')->insert($filas);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
