<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LimpiarBaseDatos extends Command
{
    protected $signature   = 'db:limpiar';
    protected $description = 'Limpia completamente el schema public de PostgreSQL';

    public function handle(): void
    {
        $this->info('Limpiando base de datos...');

        DB::statement('DROP SCHEMA public CASCADE');
        DB::statement('CREATE SCHEMA public');
        DB::statement('GRANT ALL ON SCHEMA public TO postgres');
        DB::statement('GRANT ALL ON SCHEMA public TO public');

        $this->info('Base de datos limpiada correctamente.');
    }
}