<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docente', function (Blueprint $table) {
            $table->increments('id_docente');
            $table->unsignedInteger('id_usuario')->unique();
            $table->string('ci', 20)->unique()->notNull();
            $table->string('nombres', 100)->notNull();
            $table->string('apellido_paterno', 100)->notNull();
            $table->string('apellido_materno', 100)->notNull();
            $table->string('nivel_academico', 100)->notNull();

            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuario')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente');
    }
};