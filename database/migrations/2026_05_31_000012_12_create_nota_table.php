<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nota', function (Blueprint $table) {
            $table->increments('id_nota');
            $table->unsignedInteger('id_examen');
            $table->unsignedInteger('id_postulante');
            $table->decimal('calificacion', 5, 2)->notNull();

            $table->unique(['id_examen', 'id_postulante']);
            $table->check('calificacion >= 0.00 AND calificacion <= 100.00');

            $table->foreign('id_examen')
                  ->references('id_examen')
                  ->on('examen')
                  ->onDelete('cascade');

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulante')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota');
    }
};