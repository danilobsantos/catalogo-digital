<?php

declare(strict_types=1);

namespace App\Domains\Content\Http\Controllers;

use Illuminate\Contracts\View\View;

final class AboutController
{
    public function index(): View
    {
        $companyData = [
            'name' => config('catalog.company.name', 'CJ Calçados'),
            'slogan' => 'Qualidade que acompanha sua caminhada.',
            'history_years' => '20+',
            'address' => [
                'street' => 'Av. José Antônio dos Santos, 203',
                'neighborhood' => 'Jardim Planalto',
                'city' => 'Guaxupé',
                'state' => 'MG',
                'postal_code' => '37832-192',
                'full' => 'Av. José Antônio dos Santos, 203 - Jardim Planalto, Guaxupé - MG, 37832-192',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query='.urlencode('Av. José Antônio dos Santos, 203 - Jardim Planalto, Guaxupé - MG, 37832-192'),
            ],
            'stats' => [
                ['value' => '+20', 'unit' => 'Anos', 'label' => 'Tradição Calçadista', 'desc' => 'Duas décadas dedicadas à excelência no calçado'],
                ['value' => '100%', 'unit' => 'Própria', 'label' => 'Fabricação Nacional', 'desc' => 'Estrutura fabril própria e controle rigoroso em Guaxupé-MG'],
                ['value' => '1ª', 'unit' => 'Linha', 'label' => 'Couro Selecionado', 'desc' => 'Matéria-prima nobre de alta espessura e maciez'],
                ['value' => 'C.A.', 'unit' => 'Normas', 'label' => 'Modelos Certificados', 'desc' => 'Linha de segurança homologada para o trabalho'],
            ],
            'pillars' => [
                [
                    'title' => 'Conforto',
                    'desc' => 'Formas anatômicas e forração com respiro pensadas para quem passa o dia inteiro de pé.',
                ],
                [
                    'title' => 'Resistência',
                    'desc' => 'Couro legítimo de alta densidade e costuras reforçadas para enfrentar os terrenos mais exigentes.',
                ],
                [
                    'title' => 'Tecnologia',
                    'desc' => 'Solados com absorção de impacto, alta aderência e formulação especial antiderrapante.',
                ],
                [
                    'title' => 'Qualidade',
                    'desc' => 'Controle de qualidade minucioso em cada par, da seleção do lote de couro à revisão da costura.',
                ],
                [
                    'title' => 'Tradição',
                    'desc' => 'Duas décadas de paixão pela fabricação e fornecimento de insumos para a indústria calçadista.',
                ],
            ],
            'differentials' => [
                ['title' => 'Mais de 20 anos de experiência', 'desc' => 'Know-how aprofundado na rotina e necessidades do trabalhador e lojista.'],
                ['title' => 'Fabricação própria', 'desc' => 'Estrutura fabril própria em Guaxupé/MG com rigoroso controle de etapas.'],
                ['title' => 'Couro selecionado', 'desc' => 'Lotes selecionados à mão com espessura e maciez ideais para calçados duráveis.'],
                ['title' => 'Alto padrão de acabamento', 'desc' => 'Pespontos precisos, ilhoses reforçados e colagem sob pressão controlada.'],
                ['title' => 'Solados resistentes', 'desc' => 'Compostos flexíveis e resistentes à abrasão em campo, obra e asfalto.'],
                ['title' => 'Conforto para o dia todo', 'desc' => 'Palmilhas macias e cano acolchoado para amortecer cada impacto.'],
                ['title' => 'Trabalho e lazer', 'desc' => 'Design versátil que transita com naturalidade do canteiro à cidade.'],
                ['title' => 'Excelente custo-benefício', 'desc' => 'Venda direta e distribuição que garantem a melhor relação entre durabilidade e preço.'],
            ],
        ];

        return view('public.content.about', [
            'company' => $companyData,
        ]);
    }
}
