<?php

declare(strict_types=1);

namespace App\Traits;

use App\Domains\Company\Database\Scopes\CompanyScope;
use App\Domains\Company\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Adiciona a relação `company()` + global scope `CompanyScope` a Models de domínio.
 *
 * Restrições:
 *  - O Model deve ter coluna `company_id` (FK -> companies.id).
 *  - Não aplicar em User, Company, Role/Permission (essas tabelas são "raiz").
 */
trait HasCompanyScope
{
    public static function bootHasCompanyScope(): void
    {
        static::addGlobalScope(new CompanyScope);

        static::creating(function ($model): void {
            if (empty($model->company_id)) {
                $model->company_id = CompanyContext::id();
            }
        });
    }

    /** @return BelongsTo<Company, self> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Escopo local para ignorar o global scope em queries administrativas.
     *
     * @template T of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<T>  $query
     * @return Builder<T>
     */
    public function scopeWithoutCompanyScope($query)
    {
        return $query->withoutGlobalScope(CompanyScope::class);
    }
}
