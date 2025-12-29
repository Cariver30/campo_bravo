<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = database_path('cava_seed.sql');

        if (! File::exists($sqlPath)) {
            $this->command?->warn('El archivo cava_seed.sql no existe, se omitió el seed de vinos.');
            return;
        }

        $sql = File::get($sqlPath);

        // Ajustar instrucciones al dialecto MySQL.
        $sql = str_replace(
            ['PRAGMA foreign_keys=OFF;', 'PRAGMA foreign_keys=ON;', '"'],
            ['', '', '`'],
            $sql
        );

        $sql = 'SET FOREIGN_KEY_CHECKS=0;' . PHP_EOL
            . $sql . PHP_EOL
            . 'SET FOREIGN_KEY_CHECKS=1;';

        DB::unprepared($sql);
    }
}
