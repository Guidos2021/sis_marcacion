<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('dui')->unique();  
            $table->integer('id_marcador')->unique();
            $table->string('nombre');
            $table->string('cargo');
            $table->enum('distrito', ['Zacatecoluca', 'San Juan Nonualco', 'San Rafael Obrajuelo'])
                ->default('Zacatecoluca'); // Campo con valores predefinidos
            $table->boolean('estado')->default(1); // 1 = Activo, 0 = Inactivo
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');

        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
