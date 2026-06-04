<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo', function (Blueprint $table) {
            $table->increments('id_grupo');
            $table->unsignedInteger('id_materia');
            $table->unsignedInteger('id_docente');
            $table->string('nombre_grupo', 20)->notNull();
            $table->integer('capacidad_maxima')->default(70);
            $table->string('gestion', 10)->notNull();

            $table->check('capacidad_maxima <= 70');

            $table->foreign('id_materia')
                  ->references('id_materia')
                  ->on('materia')
                  ->onDelete('restrict');

            $table->foreign('id_docente')
                  ->references('id_docente')
                  ->on('docente')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};