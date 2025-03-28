<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->string('dui');
            $table->string('id_marcador');
            $table->string('nombre');
            $table->date('fecha');
            $table->string('horario');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->time('hora_tardia')->nullable();
            $table->time('hora_temprana')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
};
