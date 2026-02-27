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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',45);
            $table->string('primer_apellido',45);
            $table->string('segundo_apellido',45);
            $table->string('numero_documento',45)->unique();
            $table->string('direccion',80);
            $table->string('correo',150);
            $table->string('telefono',45);
            $table->date('fecha_nacimiento');
            $table->dateTime('fecha_cita')->nullable();
            $table->decimal('saldo', 10, 2)->default(0);
            
            
            $table->foreignId('tipo_documento_id')->constrained('tipo_documentos')->restrictOnDelete();
            $table->foreignId('eps_id')->constrained('eps')->restrictOnDelete();
            $table->foreignId('sede_id')->constrained('sedes')->restrictOnDelete();
            
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
