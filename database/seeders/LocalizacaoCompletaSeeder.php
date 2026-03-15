<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalizacaoCompletaSeeder extends Seeder
{
    public function run()
    {
        // 1️⃣ País
        $brasilId = DB::table('loc_paises')->updateOrInsert(
            ['nome' => 'Brasil'],
            ['codigo' => 'BR', 'created_at' => now(), 'updated_at' => now()]
        );
        $brasilId = DB::table('loc_paises')->where('nome', 'Brasil')->value('id');

        // 2️⃣ Estado
        $rjId = DB::table('loc_estados')->updateOrInsert(
            ['nome' => 'Rio de Janeiro', 'pais_id' => $brasilId],
            ['codigo' => 'RJ', 'created_at' => now(), 'updated_at' => now()]
        );
        $rjId = DB::table('loc_estados')->where('nome', 'Rio de Janeiro')->value('id');

        // 3️⃣ Cidade
        $rioCidadeId = DB::table('loc_cidades')->updateOrInsert(
            ['nome' => 'Rio de Janeiro', 'estado_id' => $rjId],
            ['created_at' => now(), 'updated_at' => now()]
        );
        $rioCidadeId = DB::table('loc_cidades')->where('nome', 'Rio de Janeiro')->value('id');

        // 4️⃣ Zonas
        $zonas = ['Zona Sul', 'Zona Norte', 'Zona Oeste', 'Centro'];
        $zonaIds = [];
        foreach ($zonas as $zona) {
            DB::table('loc_zonas')->updateOrInsert(
                ['nome' => $zona, 'cidade_id' => $rioCidadeId],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $zonaIds[$zona] = DB::table('loc_zonas')
                                 ->where('nome', $zona)
                                 ->where('cidade_id', $rioCidadeId)
                                 ->value('id');
        }

        // 5️⃣ Bairros por zona
        $bairrosPorZona = [
            'Zona Sul' => [
                'Copacabana', 'Ipanema', 'Leblon', 'Botafogo', 'Flamengo',
                'Laranjeiras', 'Glória', 'Humaitá', 'Urca', 'Leme',
                'Gávea', 'Jardim Botânico', 'Lagoa'
            ],
            'Zona Norte' => [
                'Tijuca', 'Méier', 'Vila Isabel', 'Grajaú', 'Maracanã', 'São Cristóvão'
            ],
            'Zona Oeste' => [
                'Barra da Tijuca', 'Recreio', 'Jacarepaguá', 'Campo Grande', 'Bangu'
            ],
            'Centro' => [
                'Centro', 'Lapa', 'Santa Teresa', 'Catete', 'Glória'
            ],
        ];

        foreach ($bairrosPorZona as $zonaNome => $bairros) {
            $zonaId = $zonaIds[$zonaNome] ?? null;
            if (!$zonaId) continue;

            foreach ($bairros as $bairro) {
                DB::table('loc_bairros')->updateOrInsert(
                    [
                        'nome' => $bairro,
                        'cidade_id' => $rioCidadeId,
                        'zona_id' => $zonaId
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }

        $this->command->info('Localizações do Rio de Janeiro cadastradas com sucesso!');
    }
}
