<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP SCHEMA public CASCADE');
        DB::statement('CREATE SCHEMA public');
        DB::statement('GRANT ALL ON SCHEMA public TO postgres');
        DB::statement('GRANT ALL ON SCHEMA public TO public');

        Schema::create('rol', function (Blueprint $table) {
            $table->increments('id_rol');
            $table->string('nombre', 50)->unique()->notNull();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rol');
    }
};