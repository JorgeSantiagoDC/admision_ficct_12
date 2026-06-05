<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrera', function (Blueprint $table) {
            $table->increments('id_carrera');
            $table->string('nombre', 100)->unique()->notNull();
            $table->integer('cupo_maximo')->notNull();
        });

        DB::statement('ALTER TABLE carrera ADD CONSTRAINT chk_cupo_maximo CHECK (cupo_maximo > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('carrera');
    }
};

