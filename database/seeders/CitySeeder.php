<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/municipios_seed.sql');

        if (File::exists($path)) {
            $sql = File::get($path);
            DB::unprepared($sql);

            $this->command->info('Arquivo SQL executado com sucesso!');
        } else {
            $this->command->error('Arquivo SQL não encontrado.');
        }
    }
}
