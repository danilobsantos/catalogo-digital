<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Company\Models\Company;
use App\Domains\Content\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Popula páginas institucionais pré-definidas para a CJ Calçados.
 *
 * Idempotente: usa `updateOrCreate` por slug.
 */
final class PageSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('slug', 'cj-calcados')->first();
        if ($company === null) {
            return;
        }

        $rows = [
            [
                'slug' => 'sobre',
                'title' => 'Sobre a CJ Calçados',
                'subtitle' => 'Tradição, qualidade e conforto em cada passo.',
                'content' => "## Nossa história\n\nA CJ Calçados é uma empresa familiar com raízes na tradição do calçado de couro. Há décadas vestimos o trabalhador brasileiro com peças selecionadas, pensadas para durar.\n\n- **Couro selecionado** matéria-prima brasileira premium.\n- **Solado robusto** testado em campo, do campo à construção civil.\n- **Acabamento manual** costuras reforçadas, palmilha antibacteriana.\n\nCatálogo digital, atendimento direto via WhatsApp e parcerias com lojistas em todo o território nacional.",
                'meta_title' => 'Sobre — CJ Calçados',
                'meta_description' => 'Empresa familiar há décadas fabricando calçados de couro premium. Tradição, qualidade e conforto em cada passo.',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'slug' => 'politica-privacidade',
                'title' => 'Política de Privacidade',
                'subtitle' => 'Como tratamos seus dados em nossa plataforma.',
                'content' => "## Resumo\n\nColetamos somente os dados necessários para processar seu pedido, enviar comunicações autorizadas ou cumprir obrigações legais.\n\n### Dados que coletamos\n\n- Nome, e-mail e telefone (informados por você no formulário de contato).\n- Cookies e logs HTTP para segurança e analytics.\n\n### Pra que usamos\n\nPara responder sua mensagem, preparar orçamentos, e cumprir obrigações fiscais.\n\n### Seus direitos (LGPD)\n\nVocê pode solicitar acesso, correção ou exclusão dos seus dados pelo e-mail indicado em nosso site.",
                'meta_title' => 'Política de Privacidade — CJ Calçados',
                'meta_description' => 'Como tratamos seus dados — LGPD, segurança, transparência.',
                'sort_order' => 20,
                'is_active' => true,
            ],
            [
                'slug' => 'termos',
                'title' => 'Termos de Uso',
                'subtitle' => 'Condições para acesso ao catálogo digital.',
                'content' => "## Bem-vindo\n\nAo acessar este site você concorda com as condições descritas nesta página.\n\n### Uso permitido\n\n- Navegação pessoal e comercial do catálogo.\n- Reproduto empregando-se fotografia e informação do produto sob prévia autorização por escrito.\n\n### Limitação de responsabilidade\n\nAs imagens e descrições são representativas e podem sofrer alterações sem aviso prévio. As indicações comerciais integradas via WhatsApp não configuram venda bilática.",
                'meta_title' => 'Termos de Uso — CJ Calçados',
                'meta_description' => 'Condições de uso do catálogo digital e das informações exibidas.',
                'sort_order' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($rows as $row) {
            Page::withoutCompanyScope()->updateOrCreate(
                ['company_id' => $company->id, 'slug' => $row['slug']],
                $row + ['company_id' => $company->id],
            );
        }
    }
}
