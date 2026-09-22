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
                'title' => 'Quem Somos — CJ Calçados',
                'subtitle' => 'Mais de 20 anos transformando tradição em qualidade.',
                'content' => "## Nossa História\n\nA CJ nasceu da vivência de quem respira o setor calçadista há duas décadas. São mais de 20 anos fornecendo insumos de excelência para a indústria local, construindo uma trajetória sólida baseada em dedicação, confiança e compromisso.\n\nGuiados por essa experiência, evoluímos e passamos a fabricar botinas de segurança e passeio que unem:\n\n- **Conforto**\n- **Resistência**\n- **Tecnologia**\n- **Qualidade**\n- **Tradição**\n\nDa seleção rigorosa da matéria-prima ao acabamento final, cada detalhe é desenvolvido para entregar produtos duráveis, seguros e confortáveis.\n\nMais do que fabricar calçados, preservamos uma história construída com trabalho, respeito e paixão.\n\n### Por que escolher a CJ?\n\n- Mais de 20 anos de experiência\n- Fabricação própria\n- Couro selecionado\n- Alto padrão de acabamento\n- Solados resistentes\n- Conforto para o dia todo\n- Modelos para trabalho e lazer\n- Excelente custo-benefício\n\n### Fábrica e Localização\n\nAv. José Antônio dos Santos, 203 - Jardim Planalto, Guaxupé - MG, CEP 37832-192.",
                'meta_title' => 'Quem Somos — CJ Calçados | Fábrica Própria em Guaxupé-MG',
                'meta_description' => 'Há mais de 20 anos fabricando botinas de couro legítimo de segurança e passeio com conforto, durabilidade e tradição em Guaxupé-MG.',
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
