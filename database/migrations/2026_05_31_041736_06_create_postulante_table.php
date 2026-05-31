<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postulante', function (Blueprint $table) {
            $table->increments('id_postulante');
            $table->unsignedInteger('id_usuario')->unique();
            $table->string('ci', 20)->unique()->notNull();
            $table->string('nombres', 100)->notNull();
            $table->string('apellido_paterno', 100)->notNull();
            $table->string('apellido_materno', 100)->notNull();
            $table->date('fecha_nacimiento')->notNull();
            $table->char('sexo', 1)->notNull();
            $table->text('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->unique()->notNull();
            $table->string('colegio_procedencia', 150)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->unsignedInteger('id_carrera_opcion1');
            $table->unsignedInteger('id_carrera_opcion2')->nullable();
            $table->decimal('promedio_final', 5, 2)->default(0.00);
            $table->string('estado_admision', 20)->default('Pendiente');

            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuario')
                  ->onDelete('cascade');

            $table->foreign('id_carrera_opcion1')
                  ->references('id_carrera')
                  ->on('carrera')
                  ->onDelete('restrict');

            $table->foreign('id_carrera_opcion2')
                  ->references('id_carrera')
                  ->on('carrera')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postulante');
    }
};