<?php

declare(strict_types=1);

namespace App\Domains\Company\Models;

use App\Models\User;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Tenant raiz — toda entidade de domínio se vincula a uma Company via company_id.
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 */
final class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    use LogsActivity;

    protected $fillable = [
        'slug',
        'name',
        'legal_name',
        'document',
        'slogan',
        'about',
        'logo_path',
        'favicon_path',
        'email_primary',
        'phone_primary',
        'whatsapp_number',
        'social',
        'address',
        'theme_color',
        'dark_mode_default',
        'is_active',
    ];

    protected $casts = [
        'social' => 'array',
        'address' => 'array',
        'dark_mode_default' => 'bool',
        'is_active' => 'bool',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'name', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('company');
    }

    /** @return BelongsToMany<User, $this> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'companies_users')
            ->withPivot(['is_owner'])
            ->withTimestamps();
    }

    /** @return HasMany<User, $this> */
    public function ownerUsers(): HasMany
    {
        return $this->hasMany(User::class, 'active_company_id');
    }
}
