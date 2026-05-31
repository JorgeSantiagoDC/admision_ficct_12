<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materia', function (Blueprint $table) {
            $table->increments('id_materia');
            $table->string('nombre', 100)->unique()->notNull();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materia');
    }
};