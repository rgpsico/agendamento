<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalizacaoSeeder extends Seeder
{
    public function run()
    {
        // 1️⃣ País
        $brasilId = DB::table('countries')->insertGetId([
            'name' => 'Brasil',
            'code' => 'BR',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2️⃣ Estado
        $rjId = DB::table('states')->insertGetId([
            'country_id' => $brasilId,
            'name' => 'Rio de Janeiro',
            'code' => 'RJ',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3️⃣ Cidade
        $rioCidadeId = DB::table('cities')->insertGetId([
            'state_id' => $rjId,
            'name' => 'Rio de Janeiro',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4️⃣ Zonas
        $zonas = [
            'Zona Sul',
            'Zona Norte',
            'Zona Oeste',
            'Centro',
        ];

        $zonaIds = [];
        foreach ($zonas as $zona) {
            $zonaIds[$zona] = DB::table('zones')->insertGetId([
                'city_id' => $rioCidadeId,
                'name' => $zona,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5️⃣ Bairros da Zona Sul
        $bairrosZonaSul = [
            'Copacabana',
            'Ipanema',
            'Leblon',
            'Botafogo',
            'Flamengo',
            'Laranjeiras',
            'Glória',
            'Humaitá',
            'Urca',
            'Leme',
            'Gávea',
            'Jardim Botânico',
            'Lagoa'
        ];

        foreach ($bairrosZonaSul as $bairro) {
            DB::table('neighborhoods')->insert([
                'city_id' => $rioCidadeId,
                'zone_id' => $zonaIds['Zona Sul'],
                'name' => $bairro,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 6️⃣ Você pode adicionar bairros das outras zonas depois
    }
}
