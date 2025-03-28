<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('marcaciones', function (Blueprint $table) {
            $table->id();
            
            $table->integer('id_marcador');
            
            $table->date('fecha'); // Se almacena automáticamente en formato YYYY-MM-DD
            $table->time('hora_entrada')->nullable();  // Hacer nullable la columna
            $table->time('hora_salida')->nullable(); 
            $table->time('entrada_tardia')->nullable();
            $table->time('salida_temprana')->nullable();
            $table->text('excepciones')->nullable();
            
            $table->foreign('id_marcador')->references('id_marcador')->on('empleados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marcaciones');
    }

    public function getHoraEntradaAttribute($value)
    {
        return date('H:i', strtotime($value));
    }

    public function getHoraSalidaAttribute($value)
    {
        return date('H:i', strtotime($value));
    }

    public function getEntradaTardiaAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

    public function getSalidaTempranaAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

};
