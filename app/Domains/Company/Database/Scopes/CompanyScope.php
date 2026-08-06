<?php

declare(strict_types=1);

namespace App\Domains\Company\Database\Scopes;

use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Filtra todas as queries por `company_id = CompanyContext::id()`.
 *
 * Para acesso cross-tenant usar `Model::withoutGlobalScope(CompanyScope::class)`
 * ou o helper {@see HasCompanyScope::scopeWithoutCompanyScope()}.
 */
final class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $companyId = CompanyContext::id();

        if ($companyId === null) {
            // Em boot de teste/seed sem contexto: não filtra.
            return;
        }

        $builder->where($model->getTable().'.company_id', $companyId);
    }
}
