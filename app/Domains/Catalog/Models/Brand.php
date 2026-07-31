<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Models;

use App\Traits\HasCompanyScope;
use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Marca / sub-marca de produtos.
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 */
class Brand extends Model implements HasMedia
{
    use HasCompanyScope;

    /** @use HasFactory<BrandFactory> */
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'company_id',
        'slug',
        'name',
        'logo_path',
        'description',
        'website_url',
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
            ->useLogName('catalog:brand');
    }

    /** @return HasMany<Product, self> */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']);
    }
}
