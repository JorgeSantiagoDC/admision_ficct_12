<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrera', function (Blueprint $table) {
            $table->increments('id_carrera');
            $table->string('nombre', 100)->unique();
            $table->integer('cupo_maximo');

            $table->check('cupo_maximo > 0');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrera');
    }
};

