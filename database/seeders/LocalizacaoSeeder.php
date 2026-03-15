<?php 

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalizacaoSeeder  extends Seeder
{
    public function run()
    {
        // País
        $brasilId = DB::table('localizacoes')->insertGetId([
            'nome' => 'Brasil',
            'tipo' => 'pais',
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Estado
        $rjId = DB::table('localizacoes')->insertGetId([
            'nome' => 'Rio de Janeiro',
            'tipo' => 'estado',
            'parent_id' => $brasilId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cidade
        $rioCidadeId = DB::table('localizacoes')->insertGetId([
            'nome' => 'Rio de Janeiro',
            'tipo' => 'cidade',
            'parent_id' => $rjId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Zonas
        $zonas = ['Zona Sul', 'Zona Norte', 'Zona Oeste', 'Centro'];
        $zonaIds = [];
        foreach ($zonas as $zona) {
            $zonaIds[$zona] = DB::table('localizacoes')->insertGetId([
                'nome' => $zona,
                'tipo' => 'zona',
                'parent_id' => $rioCidadeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Bairros da Zona Sul
        $bairrosZonaSul = [
            'Copacabana', 'Ipanema', 'Leblon', 'Botafogo', 'Flamengo',
            'Laranjeiras', 'Glória', 'Humaitá', 'Urca', 'Leme',
            'Gávea', 'Jardim Botânico', 'Lagoa'
        ];

        foreach ($bairrosZonaSul as $bairro) {
            DB::table('localizacoes')->insert([
                'nome' => $bairro,
                'tipo' => 'bairro',
                'parent_id' => $zonaIds['Zona Sul'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
