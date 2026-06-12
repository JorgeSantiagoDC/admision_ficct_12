<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examen', function (Blueprint $table) {
            $table->increments('id_examen');
            $table->unsignedInteger('id_grupo');
            $table->string('nombre_evaluacion', 50)->notNull();
            $table->integer('porcentaje_ponderado')->notNull();

            $table->foreign('id_grupo')->references('id_grupo')->on('grupo')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE examen ADD CONSTRAINT chk_porcentaje CHECK (porcentaje_ponderado > 0 AND porcentaje_ponderado <= 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('examen');
    }
};