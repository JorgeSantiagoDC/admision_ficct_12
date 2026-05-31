<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examen', function (Blueprint $table) {
            $table->increments('id_examen');
            $table->unsignedInteger('id_grupo');
            $table->string('nombre_evaluacion', 50)->notNull();
            $table->integer('porcentaje_ponderado')->notNull();

            $table->check('porcentaje_ponderado > 0 AND porcentaje_ponderado <= 100');

            $table->foreign('id_grupo')
                  ->references('id_grupo')
                  ->on('grupo')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen');
    }
};