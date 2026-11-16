<?php

namespace App\Support;

use DateTimeInterface;
use RuntimeException;
use ZipArchive;

/**
 * Gera um .xlsx simples (várias abas, cabeçalho em negrito, moeda e datas formatadas) sem dependências.
 * A biblioteca de Excel do composer (maatwebsite/excel 1.x + PHPExcel) não roda no PHP 8.
 *
 * Uso:
 *   $xlsx = new PlanilhaXlsx();
 *   $xlsx->aba('Receitas', ['Data', 'Valor'], [[now(), 10.5]], ['data', 'moeda']);
 *   return $xlsx->download('financeiro.xlsx');
 *
 * Tipos de coluna: 'texto' (padrão), 'moeda', 'numero', 'data', 'percentual'.
 */
class PlanilhaXlsx
{
    private const ESTILO = ['texto' => 0, 'cabecalho' => 1, 'moeda' => 2, 'data' => 3, 'numero' => 4, 'percentual' => 5, 'negrito' => 1, 'moeda_negrito' => 6];

    /** @var array<int, array{nome:string, cabecalho:array, linhas:array, tipos:array, larguras:array}> */
    private array $abas = [];

    /**
     * @param  array<int, string>  $cabecalho
     * @param  array<int, array<int, mixed>>  $linhas
     * @param  array<int, string>  $tipos  tipo de cada coluna (ver doc da classe)
     */
    public function aba(string $nome, array $cabecalho, array $linhas, array $tipos = []): static
    {
        // Nome de aba: até 31 caracteres, sem : \ / ? * [ ]
        $nome = mb_substr(preg_replace('#[:\\\\/?*\[\]]#', ' ', $nome), 0, 31);

        $larguras = [];
        foreach (array_merge([$cabecalho], $linhas) as $linha) {
            foreach (array_values($linha) as $i => $valor) {
                $texto = $valor instanceof DateTimeInterface ? '00/00/0000' : (is_float($valor) ? number_format($valor, 2) . '    ' : (string) $valor);
                $larguras[$i] = min(60, max($larguras[$i] ?? 8, mb_strlen($texto) + 2));
            }
        }

        $this->abas[] = compact('nome', 'cabecalho', 'linhas', 'tipos', 'larguras');

        return $this;
    }

    /** Resposta HTTP de download. */
    public function download(string $arquivo)
    {
        $caminho = $this->salvar();

        return response()->download($caminho, $arquivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /** Grava num arquivo temporário e devolve o caminho. */
    public function salvar(): string
    {
        if (empty($this->abas)) {
            throw new RuntimeException('Planilha sem abas.');
        }

        $caminho = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        if ($zip->open($caminho, ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Não foi possível criar a planilha.');
        }

        $n = count($this->abas);
        $zip->addFromString('[Content_Types].xml', $this->contentTypes($n));
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>');
        $zip->addFromString('xl/workbook.xml', $this->workbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRels($n));
        $zip->addFromString('xl/styles.xml', $this->estilos());

        foreach ($this->abas as $i => $aba) {
            $zip->addFromString('xl/worksheets/sheet' . ($i + 1) . '.xml', $this->planilha($aba));
        }

        $zip->close();

        return $caminho;
    }

    private function contentTypes(int $n): string
    {
        $sheets = '';
        for ($i = 1; $i <= $n; $i++) {
            $sheets .= '<Override PartName="/xl/worksheets/sheet' . $i . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . $sheets . '</Types>';
    }

    private function workbook(): string
    {
        $sheets = '';
        foreach ($this->abas as $i => $aba) {
            $sheets .= '<sheet name="' . $this->esc($aba['nome']) . '" sheetId="' . ($i + 1) . '" r:id="rId' . ($i + 1) . '"/>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>' . $sheets . '</sheets></workbook>';
    }

    private function workbookRels(int $n): string
    {
        $rels = '';
        for ($i = 1; $i <= $n; $i++) {
            $rels .= '<Relationship Id="rId' . $i . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $i . '.xml"/>';
        }
        $rels .= '<Relationship Id="rId' . ($n + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . $rels . '</Relationships>';
    }

    /** Estilos: 0 normal, 1 negrito com fundo, 2 moeda R$, 3 data dd/mm/aaaa, 4 número, 5 percentual, 6 moeda negrito. */
    private function estilos(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="2">'
            . '<numFmt numFmtId="164" formatCode="&quot;R$&quot;\ #,##0.00;[Red]\-&quot;R$&quot;\ #,##0.00"/>'
            . '<numFmt numFmtId="165" formatCode="dd/mm/yyyy"/>'
            . '</numFmts>'
            . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFE8EEF7"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="7">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>'
            . '<xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '<xf numFmtId="165" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '<xf numFmtId="4" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '<xf numFmtId="10" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '<xf numFmtId="164" fontId="1" fillId="0" borderId="0" xfId="0" applyNumberFormat="1" applyFont="1"/>'
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private function planilha(array $aba): string
    {
        $cols = '';
        foreach ($aba['larguras'] as $i => $largura) {
            $cols .= '<col min="' . ($i + 1) . '" max="' . ($i + 1) . '" width="' . $largura . '" customWidth="1"/>';
        }

        $linhasXml = $this->linha(1, $aba['cabecalho'], [], true);
        foreach ($aba['linhas'] as $r => $linha) {
            $linhasXml .= $this->linha($r + 2, $linha, $aba['tipos']);
        }

        $ultimaColuna = $this->coluna(max(count($aba['cabecalho']), 1) - 1);
        $filtro = count($aba['linhas']) && count($aba['cabecalho']) > 1
            ? '<autoFilter ref="A1:' . $ultimaColuna . (count($aba['linhas']) + 1) . '"/>'
            : '';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . ($cols ? '<cols>' . $cols . '</cols>' : '')
            . '<sheetData>' . $linhasXml . '</sheetData>'
            . $filtro
            . '</worksheet>';
    }

    private function linha(int $numero, array $valores, array $tipos, bool $cabecalho = false): string
    {
        $xml = '<row r="' . $numero . '">';
        foreach (array_values($valores) as $i => $valor) {
            $ref = $this->coluna($i) . $numero;
            $tipo = $cabecalho ? 'cabecalho' : ($tipos[$i] ?? 'texto');

            // Linha de total: texto começando com "TOTAL" deixa a linha em negrito.
            if (! $cabecalho && is_string($valores[0] ?? null) && str_starts_with($valores[0], 'TOTAL')) {
                $tipo = $tipo === 'moeda' ? 'moeda_negrito' : ((is_string($valor) && $valor !== '') ? 'negrito' : $tipo);
            }

            $estilo = self::ESTILO[$tipo] ?? 0;
            if ($tipo === 'numero' && is_int($valor)) {
                $estilo = 0; // inteiro: sem casas decimais
            }

            if ($valor === null || $valor === '') {
                $xml .= '<c r="' . $ref . '" s="' . $estilo . '"/>';
            } elseif ($valor instanceof DateTimeInterface) {
                $xml .= '<c r="' . $ref . '" s="' . self::ESTILO['data'] . '"><v>' . $this->serialData($valor) . '</v></c>';
            } elseif ((is_int($valor) || is_float($valor)) && ! $cabecalho) {
                $xml .= '<c r="' . $ref . '" s="' . $estilo . '"><v>' . $valor . '</v></c>';
            } else {
                $xml .= '<c r="' . $ref . '" s="' . $estilo . '" t="inlineStr"><is><t xml:space="preserve">' . $this->esc((string) $valor) . '</t></is></c>';
            }
        }

        return $xml . '</row>';
    }

    /** Data do Excel: dias desde 30/12/1899. */
    private function serialData(DateTimeInterface $data): float
    {
        $base = new \DateTimeImmutable('1899-12-30 00:00:00', $data->getTimezone());
        $dias = (int) $base->diff(\DateTimeImmutable::createFromInterface($data)->setTime(0, 0))->format('%a');

        return (float) $dias;
    }

    private function coluna(int $indice): string
    {
        $nome = '';
        for ($i = $indice + 1; $i > 0; $i = intdiv($i - 1, 26)) {
            $nome = chr(65 + ($i - 1) % 26) . $nome;
        }

        return $nome;
    }

    private function esc(string $texto): string
    {
        // Remove caracteres de controle que o XML não aceita.
        $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $texto) ?? '';

        return htmlspecialchars($texto, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
