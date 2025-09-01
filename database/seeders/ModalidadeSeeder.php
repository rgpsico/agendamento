<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modalidade;

class ModalidadeSeeder extends Seeder
{
    public function run(): void
    {
        // Modalidades de esportes de praia
        $modalidadesPraia = [
            'Aula de Surf',
            'Aula de Body Board',
            'Boxe ao ar livre',
            'Stand Up Paddle',
            'Por do Sol',
        ];

        foreach ($modalidadesPraia as $nome) {
            Modalidade::firstOrCreate(['nome' => $nome]);
        }

        // Modalidades da área pet
        // $modalidadesPet = [
        //     'Adestrador',
        //     'Passeador de Cães',
        //     'Veterinário',
        //     'Hospedagem de Pets',
        //     'Banho e Tosa',
        // ];

        // foreach ($modalidadesPet as $nome) {
        //     Modalidade::firstOrCreate(['nome' => $nome]);
        // }
    }
}
