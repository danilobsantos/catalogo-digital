<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Company\Models\Company;
use App\Models\User;
use App\Traits\CompanyContext;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeder inicial — empresa padrão + papéis + permissões + super-admin.
 *
 * IDEMPOTENTE: reexecutável em dev sem duplicar registros.
 */
final class BootstrapSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $company = $this->upsertCompany();
        $this->upsertRolesAndPermissions();

        $admin = $this->upsertSuperAdmin($company);
        $this->upsertCompanyAdmin($company);
        $this->upsertEditor($company);

        // Fallback para console (seeds, jobs) — usado por HasCompanyScope::creating
        CompanyContext::setFallback($admin->active_company_id);

        // Importa catálogo de DOCX/JPG em material/.
        $this->call(CatalogSeeder::class);
    }

    private function upsertCompany(): Company
    {
        return Company::withoutEvents(function () use (&$company): Company {
            $company = Company::updateOrCreate(
                ['slug' => 'cj-calcados'],
                [
                    'name' => config('catalog.company.name'),
                    'legal_name' => 'CJ Calçados LTDA',
                    'document' => 'CNPJ',
                    'slogan' => 'Botinas e calçados de couro com qualidade e conforto — tradição em cada passo.',
                    'about' => 'Somos a CJ Calçados. Tradição, qualidade e durabilidade em calçados de couro premium. Conheça nossa coleção de botinas, coturnos e calçados infantis.',
                    'email_primary' => 'contato@cjcalcados.com.br',
                    'phone_primary' => '+55 35 98816-0553',
                    'whatsapp_number' => config('catalog.whatsapp.number'),
                    'theme_color' => '#0a0a0a',
                    'dark_mode_default' => false,
                    'is_active' => true,
                ],
            );

            return $company;
        });
    }

    private function upsertRolesAndPermissions(): void
    {
        $roleNames = [
            'super-admin',
            'company-admin',
            'editor',
            'marketer',
        ];

        foreach ($roleNames as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }

    private function upsertSuperAdmin(Company $company): User
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@cjcalcados.com.br'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'active_company_id' => $company->id,
                'email_verified_at' => now(),
            ],
        );

        $user->companies()->syncWithoutDetaching([
            $company->id => ['is_owner' => true],
        ]);

        $user->assignRole('super-admin');

        return $user;
    }

    private function upsertCompanyAdmin(Company $company): User
    {
        $user = User::updateOrCreate(
            ['email' => 'company@cjcalcados.com.br'],
            [
                'name' => 'Gerente',
                'password' => bcrypt('password'),
                'active_company_id' => $company->id,
                'email_verified_at' => now(),
            ],
        );

        $user->companies()->syncWithoutDetaching([
            $company->id => ['is_owner' => false],
        ]);

        $user->assignRole('company-admin');

        return $user;
    }

    private function upsertEditor(Company $company): User
    {
        $user = User::updateOrCreate(
            ['email' => 'editor@cjcalcados.com.br'],
            [
                'name' => 'Editor',
                'password' => bcrypt('password'),
                'active_company_id' => $company->id,
                'email_verified_at' => now(),
            ],
        );

        $user->companies()->syncWithoutDetaching([
            $company->id => ['is_owner' => false],
        ]);

        $user->assignRole('editor');

        return $user;
    }
}
