<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->increments('id_pago');
            $table->unsignedInteger('id_postulante');
            $table->string('metodo', 50)->notNull();
            $table->decimal('monto', 10, 2)->notNull();
            $table->timestamp('fecha_pago')->useCurrent();
            $table->string('estado_pago', 20)->default('Completado');

            $table->foreign('id_postulante')
                  ->references('id_postulante')
                  ->on('postulante')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};