<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisito', function (Blueprint $table) {
            $table->increments('id_requisito');
            $table->unsignedInteger('id_postulante');
            $table->string('tipo_documento', 50)->notNull();
            $table->text('url_archivo')->notNull();
            $table->string('estado_validacion', 20)->default('Pendiente');

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulante')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisito');
    }
};