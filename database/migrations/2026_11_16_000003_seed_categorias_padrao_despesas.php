<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Despesa exige categoria (despesas_categorias), mas a tabela estava vazia e não havia tela para criá-las:
     * ninguém conseguia lançar despesa nem despesa recorrente. Estas são padrão (empresa_id nulo),
     * visíveis para todas as empresas; cada uma pode criar as suas em Financeiro > Categorias.
     */
    private const PADRAO = [
        ['Aluguel', 'Aluguel do espaço, condomínio e IPTU'],
        ['Energia elétrica', 'Conta de luz'],
        ['Água', 'Conta de água e esgoto'],
        ['Internet e telefone', 'Internet, telefone e celular'],
        ['Salários e encargos', 'Salários, comissões, FGTS, INSS, vale-transporte'],
        ['Pró-labore', 'Retirada dos sócios'],
        ['Impostos e taxas', 'Simples Nacional, ISS, taxas e alvarás'],
        ['Contabilidade', 'Honorários do contador'],
        ['Equipamentos e materiais', 'Aparelhos, acessórios e materiais de consumo'],
        ['Manutenção e limpeza', 'Reparos, produtos e serviços de limpeza'],
        ['Marketing', 'Anúncios, redes sociais e material de divulgação'],
        ['Sistemas e assinaturas', 'Softwares, aplicativos e assinaturas'],
        ['Taxas bancárias e de cartão', 'Tarifas do banco e da maquininha'],
        ['Outras despesas', null],
    ];

    public function up(): void
    {
        foreach (self::PADRAO as [$nome, $descricao]) {
            $existe = DB::table('despesas_categorias')->whereNull('empresa_id')->where('nome', $nome)->exists();
            if (! $existe) {
                DB::table('despesas_categorias')->insert([
                    'empresa_id' => null,
                    'nome'       => $nome,
                    'descricao'  => $descricao,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $nomes = array_column(self::PADRAO, 0);
        $emUso = DB::table('despesas')->pluck('categoria_id')
            ->merge(DB::table('despesas_recorrentes')->pluck('categoria_id'))->filter()->unique()->all();

        DB::table('despesas_categorias')->whereNull('empresa_id')->whereIn('nome', $nomes)
            ->whereNotIn('id', $emUso ?: [0])->delete();
    }
};
