<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Models;

use App\Traits\HasCompanyScope;
use Database\Factories\CollectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Linha / Coleção temática (Premium, Profissional, Feminina…).
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 */
class Collection extends Model
{
    use HasCompanyScope;

    /** @use HasFactory<CollectionFactory> */
    use HasFactory;
    use LogsActivity;

    protected $table = 'collections';

    protected $fillable = [
        'company_id',
        'slug',
        'name',
        'description',
        'cover_path',
        'accent_color',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'sort_order' => 'int',
        'is_active' => 'bool',
        'is_featured' => 'bool',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'name', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('catalog:collection');
    }

    /** @return HasMany<Product, self> */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
