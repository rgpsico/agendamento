<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feriado;

class FeriadosSeeder extends Seeder
{
    public function run()
    {
        $feriados = [
            ['data' => '2025-01-01', 'descricao' => 'Confraternização Universal'],
            ['data' => '2025-04-21', 'descricao' => 'Tiradentes'],
            ['data' => '2025-05-01', 'descricao' => 'Dia do Trabalhador'],
            ['data' => '2025-09-07', 'descricao' => 'Independência do Brasil'],
            ['data' => '2025-10-12', 'descricao' => 'Nossa Senhora Aparecida'],
            ['data' => '2025-11-02', 'descricao' => 'Finados'],
            ['data' => '2025-11-15', 'descricao' => 'Proclamação da República'],
            ['data' => '2025-12-25', 'descricao' => 'Natal'],
        ];

        foreach ($feriados as $feriado) {
            Feriado::updateOrCreate(
                ['data' => $feriado['data']],
                ['descricao' => $feriado['descricao']]
            );
        }
    }
}
