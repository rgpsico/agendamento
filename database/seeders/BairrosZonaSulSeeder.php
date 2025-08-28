<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BairrosZonaSulSeeder extends Seeder
{
    public function run()
    {
        $bairros = [
            'Copacabana',
            'Ipanema',
            'Leblon',
            'Botafogo',
            'Flamengo',
            'Laranjeiras',
            'Glória',
            'Catete',
            'Urca',
            'Leme',
            'Humaitá',
            'Jardim Botânico',
            'Lagoa',
            'Cosme Velho',
            'Gávea',
            'São Conrado',
        ];

        foreach ($bairros as $bairro) {
            DB::table('bairros')->insert([
                'nome' => $bairro,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
