<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Support;

use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use Throwable;

/**
 * Parser robusto dos arquivos DOCX (`INFORMAÇÕES TÉCNICAS/*.docx`) da CJ.
 *
 * Fluxo:
 *  - Lê o docx com PHPWord (Word2007 reader)
 *  - Itera parágrafos e junta runs em texto limpo
 *  - Aplica heurísticas para extrair: código, título, descrição, materiais,
 *    solas, cores, cuidados, tabela de tamanhos, certificações.
 *
 * Resiliência:
 *  - Tenta PhpWord\IOFactory; se falhar (zópera fábrica externa), faz fallback
 *    direto sobre o XML `word/document.xml` do zip.
 */
final class DocxProductParser
{
    /** @return DocxProductDto|null NULL se não foi possível extrair um código válido do nome. */
    public function parse(string $absolutePath, string $fileName): ?DocxProductDto
    {
        $codeParts = $this->parseCodeFromFileName($fileName);
        if ($codeParts === null) {
            return null;
        }

        $rawText = $this->readText($absolutePath);
        if ($rawText === null || trim($rawText) === '') {
            return null;
        }

        $normalized = $this->normalize($rawText);

        return new DocxProductDto(
            rawCode: $codeParts['code'],
            variantCode: $codeParts['variant'],
            fileName: $fileName,
            title: $this->extractTitle($normalized),
            subtitle: $this->extractSubtitle($normalized),
            shortDescription: $this->extractSectionText($normalized, 'Descrição'),
            description: $this->extractDescription($normalized),
            materials: $this->extractMaterials($normalized),
            sole: $this->extractSole($normalized),
            leather: $this->extractLeather($normalized),
            closure: $this->extractClosure($normalized),
            toeCap: $this->extractToeCap($normalized),
            approval: $this->extractApproval($normalized),
            weight: $this->extractWeight($normalized),
            care: $this->extractCare($normalized),
            sizeChart: $this->extractSizeChart($normalized),
            colors: $this->extractColors($normalized),
            features: $this->extractFeatures($normalized),
            hasCa: $this->hasCa($normalized, $fileName),
            manufacturing: $this->extractManufacturing($normalized),
        );
    }

    /**
     * @return array{code: string, variant: ?string}|null
     */
    public function parseCodeFromFileName(string $fileName): ?array
    {
        $base = pathinfo($fileName, PATHINFO_FILENAME);
        $base = Str::of($base)
            ->replace(['ç', 'Ç'], ['c', 'C'])
            ->toString();

        if (preg_match('/^(\d+)-(\d+)\b/u', $base, $m)) {
            return ['code' => $m[1], 'variant' => $m[2]];
        }

        if (preg_match('/^(\d+)\b/u', $base, $m)) {
            return ['code' => $m[1], 'variant' => null];
        }

        return null;
    }

    private function readText(string $absolutePath): ?string
    {
        if (! is_readable($absolutePath)) {
            return null;
        }

        try {
            $phpWord = IOFactory::createReader('Word2007')->load($absolutePath);
            $out = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text = (string) $element->getText();
                        if ($text !== '') {
                            $out .= Str::trim($text)."\n";
                        }
                    } elseif (method_exists($element, 'getElements')) {
                        foreach ($element->getElements() as $cell) {
                            if (method_exists($cell, 'getText')) {
                                $out .= Str::trim((string) $cell->getText())."\n";
                            }
                        }
                    }
                }
            }

            return $out;
        } catch (Throwable) {
            return $this->fallbackReadXml($absolutePath);
        }
    }

    private function fallbackReadXml(string $absolutePath): ?string
    {
        $zip = new \ZipArchive;
        if (@$zip->open($absolutePath) !== true) {
            return null;
        }
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();
        if ($xml === false) {
            return null;
        }

        // Reconstrói parágrafos como linhas separadas, depois concatena runs
        // preservando espaço entre eles (Word quebra palavras frequentemente).
        $xml = preg_replace('/<\/w:p>/u', "\n", $xml) ?? $xml;
        $xml = preg_replace('/<w:tab\b[^>]*\/>/u', "\t", $xml) ?? $xml;
        $xml = preg_replace('/<w:br\b[^>]*\/>/u', "\n", $xml) ?? $xml;

        $paragraphs = preg_split('/\n/u', $xml) ?: [];
        $lines = [];
        foreach ($paragraphs as $paragraph) {
            $segments = [];
            if (preg_match_all('/<w:t[^>]*>([^<]*)<\/w:t>/u', $paragraph, $m)) {
                $segments = $m[1];
            }
            $joined = '';
            foreach ($segments as $i => $seg) {
                $previous = $segments[$i - 1] ?? '';
                $needsSpace = $previous !== ''
                    && ! str_ends_with($previous, ' ')
                    && ! str_starts_with($seg, ' ')
                    && ! preg_match('/^[.,;:!?\-]/u', $seg);
                if ($needsSpace) {
                    $joined .= ' ';
                }
                $joined .= $seg;
            }
            $lines[] = $joined;
        }

        return implode("\n", $lines);
    }

    private function normalize(string $text): string
    {
        $text = preg_replace('/[ \t]+/u', ' ', $text) ?? $text;
        $text = preg_replace('/\n{3,}/u', "\n\n", $text) ?? $text;
        $text = preg_replace('/:\s*\n\s+/u', ': ', $text) ?? $text;

        // Rejunta quebras dentro de palavra por Leading runs visíveis (previne fragmentação).
        return trim($text);
    }

    private function extractTitle(string $text): string
    {
        return trim(Str::before($text, "\n"));
    }

    private function extractSubtitle(string $text): string
    {
        $lines = preg_split('/\n/u', $text) ?: [];
        // descarta a primeira (title); junta demais até encontrar label.
        $buffer = '';
        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if ($line === '') {
                continue;
            }
            if (preg_match('/^(Descrição|Especificações|Material|Norma|Características|Cuidados|Cor|Cores|Dimensões|Processo|Medidas)/iu', $line)) {
                break;
            }
            $buffer .= $line.' ';
        }

        return trim($buffer);
    }

    private function extractSectionText(string $text, string $sectionLabel, int $maxLines = 4): ?string
    {
        $lines = preg_split('/\n/u', $text) ?: [];
        $capture = false;
        $buffer = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            if (preg_match('/^'.preg_quote($sectionLabel, '/').'\s*:/iu', $line)) {
                $capture = true;
                $rest = trim(Str::after($line, ':'));
                if ($rest !== '') {
                    $buffer[] = $rest;
                }

                continue;
            }
            if ($capture) {
                if (preg_match('/^(Especificações|Material|Norma|Características|Cuidados|Cor|Cores|Dimensões|Processo|Medidas)/iu', $line)) {
                    break;
                }
                $buffer[] = $line;
                if (count($buffer) >= $maxLines) {
                    break;
                }
            }
        }

        return $buffer === [] ? null : implode(' ', $buffer);
    }

    private function extractDescription(string $text): ?string
    {
        $lines = preg_split('/\n/u', $text) ?: [];
        $capture = false;
        $buffer = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            if (preg_match('/^Descrição\s*:/iu', $line)) {
                $capture = true;
                $rest = trim(Str::after($line, ':'));
                if ($rest !== '') {
                    $buffer[] = $rest;
                }

                continue;
            }
            if ($capture) {
                if (preg_match('/^(Especificações|Material|Norma|Características|Cuidados|Cor|Cores|Dimensões|Processo|Medidas)/iu', $line)) {
                    break;
                }
                $buffer[] = $line;
            }
        }

        return $buffer === [] ? null : implode("\n", $buffer);
    }

    /** @return array<int, string> */
    private function extractMaterials(string $text): array
    {
        $section = $this->extractBetween($text, 'Especificações técnicas');
        if ($section === null) {
            return [];
        }

        $lines = preg_split('/\n/u', $section) ?: [];
        $materials = [];

        $inMaterials = false;

        foreach ($lines as $line) {
            $clean = trim($line);

            // Pula linha label.
            if (preg_match('/^Material\./iu', $clean) || $clean === '') {
                continue;
            }

            // Remove hífen inicial antes de testar labels.
            $testLine = $clean;
            if (str_starts_with($testLine, '-')) {
                $testLine = trim(ltrim(substr($testLine, 1)));
            }

            // Para quando muda de subseção.
            if (preg_match('/^(TAM|PESO|MEDIDA|PROCESSO|COR|CORES|DIMENS|CARACT|CUIDADOS)\b/iu', $testLine)) {
                break;
            }

            // Linha hífen-prefixada com conteúdo significativo.
            if (str_starts_with($clean, '-')) {
                $value = trim($testLine);
                if ($value === '' || strlen($value) < 3) {
                    continue;
                }

                $materials[] = $value;
                $inMaterials = true;

                continue;
            }

            if ($inMaterials && preg_match('/^([A-ZÀ-Ú][A-ZÀ-Ú\s,]+)$/', $clean, $m)) {
                $value = trim($m[1]);
                if (strlen($value) >= 3 && strlen($value) <= 60) {
                    $materials[] = $value;
                }
            }
        }

        return array_values(array_unique($materials));
    }

    private function extractSole(string $text): ?string
    {
        $section = $this->extractBetween($text, 'Especificações técnicas') ?? $text;
        $lines = preg_split('/\n/u', $section) ?: [];

        foreach ($lines as $line) {
            if (preg_match('/^-?\s*SOLA\s+(DE\s+)?([A-ZÀ-Ú][A-ZÀ-Ú\s\/]+)/u', trim($line), $m)) {
                return trim($m[2]);
            }
        }

        return null;
    }

    private function extractLeather(string $text): ?string
    {
        $section = $this->extractBetween($text, 'Especificações técnicas') ?? $text;
        $lines = preg_split('/\n/u', $section) ?: [];

        foreach ($lines as $line) {
            $clean = trim($line);
            $clean = ltrim($clean, '-');
            $clean = ltrim($clean);
            if (preg_match('/^COURO\s+([A-ZÀ-Ú][A-ZÀ-Ú\s]+?)\s*$/u', $clean, $m)) {
                return trim($m[1]);
            }
        }

        return null;
    }

    private function extractClosure(string $text): ?string
    {
        $section = $this->extractBetween($text, 'Especificações técnicas') ?? $text;
        $lines = preg_split('/\n/u', $section) ?: [];

        foreach ($lines as $line) {
            $clean = trim(ltrim(trim($line), '-'));
            if (preg_match('/^ELASTICO\s+(ABERTO|COBERTO)\b/u', $clean, $m)) {
                return 'Elástico '.Str::ucfirst(strtolower($m[1]));
            }
            if (preg_match('/^FECHAMENTO\s+([A-ZÀ-Ú\s]+?)\s*$/u', $clean, $m)) {
                return trim($m[1]);
            }
        }
        if (preg_match('/\bCADAR(?:CO|ÇO)\b/iu', $text)) {
            return 'Cadarço';
        }

        return null;
    }

    private function extractToeCap(string $text): ?string
    {
        $section = $this->extractBetween($text, 'Especificações técnicas') ?? $text;
        $lines = preg_split('/\n/u', $section) ?: [];

        foreach ($lines as $line) {
            $clean = trim(ltrim(trim($line), '-'));
            if (preg_match('/^BICO\s+(DE\s+)?([A-ZÀ-Ú]+)\s*$/u', $clean, $m)) {
                return trim($m[2]);
            }
        }
        if (preg_match('/BICO DE PVC/iu', $text)) {
            return 'PVC';
        }

        return null;
    }

    private function extractApproval(string $text): ?string
    {
        if (preg_match('/norma\s+aplic[áa]vel/iu', $text)) {
            return 'Certificado de Aprovação (CA) conforme norma aplicável';
        }

        return null;
    }

    private function extractWeight(string $text): ?string
    {
        if (preg_match('/PESO\s+APROXIMADO\s*([0-9.,]+)\s*GR?/iu', $text, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    /** @return array<int, string> */
    private function extractCare(string $text): array
    {
        $section = $this->extractBetween($text, 'Cuidados e conservação');
        if ($section === null) {
            return [];
        }

        $lines = preg_split('/\n/u', $section) ?: [];
        $care = [];
        foreach ($lines as $line) {
            $clean = trim($line);
            if ($clean === '' || $clean === '-' || strlen($clean) < 8) {
                continue;
            }
            if (str_starts_with($clean, '-')) {
                $clean = ltrim(substr($clean, 1));
                if (! str_ends_with($clean, '.')) {
                    $clean .= '.';
                }
            }
            $care[] = $clean;
        }

        return array_values(array_slice($care, 0, 8));
    }

    /** @return array<string, string> */
    private function extractSizeChart(string $text): array
    {
        $chart = [];
        if (preg_match_all('/(\d{2})\s*-\s*([0-9.,]+)\s*CM/iu', $text, $m, PREG_SET_ORDER)) {
            foreach ($m as $match) {
                $chart[(string) (int) $match[1]] = Str::replace('.', ',', $match[2]).'cm';
            }
        }

        return $chart;
    }

    /** @return array<int, string> */
    private function extractColors(string $text): array
    {
        $colors = [];
        if (preg_match('/Cor(?:es)?\s*[:.]\s*([\s\S]*?)(?=\n\s*\n|\n[A-ZÀ-Ú]{2,}:|$)/u', $text, $m)) {
            $raw = trim($m[1]);
            foreach (preg_split('/[,\n]/u', $raw) ?: [] as $color) {
                $color = trim($color);
                if ($color !== '' && strlen($color) < 30) {
                    $colors[] = $color;
                }
            }
        }

        return array_values(array_unique($colors));
    }

    /** @return array<int, string> */
    private function extractFeatures(string $text): array
    {
        $section = $this->extractBetween($text, 'Características');
        if ($section === null) {
            return [];
        }

        $lines = preg_split('/\n/u', $section) ?: [];
        $features = [];
        foreach ($lines as $line) {
            $clean = trim($line);
            if ($clean === '' || strlen($clean) < 4) {
                continue;
            }
            if (preg_match('/^(Resistência|Flexibilidade|Durabilidade|Absorção|Conforto|Excelente|Solado|Alta)/iu', $clean)) {
                $features[] = rtrim($clean, '.');
            }
        }

        return array_values(array_unique($features));
    }

    private function extractManufacturing(string $text): ?string
    {
        $section = $this->extractBetween($text, 'Processo de fabricação');
        if ($section === null) {
            return null;
        }

        return trim(Str::before($section, "\n")) ?: null;
    }

    private function hasCa(string $text, string $fileName): bool
    {
        return (bool) preg_match('/\bC\.A\b|\bCA\b|Certificado de Aprovação/iu', $text.' '.$fileName);
    }

    private function extractBetween(string $text, string $label): ?string
    {
        $lines = preg_split('/\n/u', $text) ?: [];
        $capture = false;
        $buffer = '';
        $endLabels = [
            'Características', 'Cuidados', 'Cores', 'Cor',
            'Dimensões', 'Medidas', 'Medida',
        ];
        foreach ($lines as $line) {
            $clean = trim($line);
            $isLabelLine = (bool) preg_match('/^'.preg_quote($label, '/').'/iu', $clean);

            if (! $capture && $isLabelLine) {
                $capture = true;
                $rest = trim(Str::after($clean, $label));
                $rest = ltrim($rest, ": \t");
                if ($rest !== '') {
                    $buffer .= $rest."\n";
                }

                continue;
            }
            if (! $capture) {
                continue;
            }

            // Encerra somente em marcadores fortes — outras linhas (Material., TAM, PESO)
            // permanecem no buffer para sub-extratores consumirem.
            foreach ($endLabels as $endLabel) {
                if (preg_match('/^'.preg_quote($endLabel, '/').'/iu', $clean)) {
                    break 2;
                }
            }

            $buffer .= $clean."\n";
        }

        return $buffer === '' ? null : trim($buffer);
    }
}
