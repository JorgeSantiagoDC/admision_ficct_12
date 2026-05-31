<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcion_grupo', function (Blueprint $table) {
            $table->increments('id_inscripcion');
            $table->unsignedInteger('id_postulante');
            $table->unsignedInteger('id_grupo');

            $table->unique(['id_postulante', 'id_grupo']);

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulante')
                  ->onDelete('cascade');

            $table->foreign('id_grupo')
                  ->references('id_grupo')
                  ->on('grupo')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_grupo');
    }
};