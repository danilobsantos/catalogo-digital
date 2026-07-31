<?php

declare(strict_types=1);

use App\Domains\Catalog\Support\DocxProductParser;

it('extrai código e variante do nome do arquivo', function (string $fileName, string $code, ?string $variant): void {
    $parser = app(DocxProductParser::class);
    $result = $parser->parseCodeFromFileName($fileName);

    if ($result === null) {
        expect($code)->toBe('__NONE__');

        return;
    }

    expect($result['code'])->toBe($code)
        ->and($result['variant'])->toBe($variant);
})->with([
    'code with variant' => ['4000-1 BOTINA X.docx', '4000', '1'],
    'code only' => ['4000 BOTINA X.docx', '4000', null],
    'code with multi-digit variant' => ['4031-12 BOTINA Y.docx', '4031', '12'],
    'garbage' => ['LIXO-sem-codigo.docx', '__NONE__', null],
]);

it('parser retorna null para arquivos inválidos ou vazios', function (): void {
    $parser = app(DocxProductParser::class);

    expect($parser->parse('/non-existent.docx', 'foobar.docx'))->toBeNull();
});

it('inbound seção: extrai materiais de DOCX real de exemplo', function (): void {
    $parser = app(DocxProductParser::class);

    $path = config('catalog.material_path').'/INFORMAÇÕES TECNICAS/4000 BOTINA DE SEGURANÇA SOLA DE BORRACHA COM BICO PVC COM C.A.docx';
    if (! is_readable($path)) {
        $this->markTestSkipped("Material não disponível: {$path}");
    }

    $dto = $parser->parse($path, basename($path));
    expect($dto)->not->toBeNull()
        ->and($dto->rawCode)->toBe('4000')
        ->and($dto->sole)->toBe('BORRACHA')
        ->and($dto->leather)->not->toBeNull()
        ->and($dto->hasCa)->toBeTrue()
        ->and($dto->sizeChart)->toHaveCount(11)
        ->and($dto->care)->toHaveCount(6);
});
