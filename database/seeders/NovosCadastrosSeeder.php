<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

final class NovosCadastrosSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('catalog:import-novos-cadastros', [], $this->command?->getOutput());
    }
}
