<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Models;

use App\Traits\HasCompanyScope;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Produto (botina/coturno/bota) do catálogo.
 *
 * @property int $id
 * @property string $code
 * @property string $slug
 * @property string $name
 */
class Product extends Model implements HasMedia
{
    use HasCompanyScope;

    /** @use HasFactory<ProductFactory> */
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'category_id',
        'brand_id',
        'collection_id',
        'code',
        'variant_code',
        'slug',
        'name',
        'subtitle',
        'short_description',
        'description',
        'materials',
        'care_instructions',
        'size_chart',
        'specs',
        'features',
        'colors',
        'sole',
        'leather',
        'closure',
        'toe_cap',
        'approvals',
        'weight_grams',
        'has_ca',
        'ca_number',
        'ca_validity',
        'is_active',
        'is_featured',
        'is_new',
        'is_bestseller',
        'sort_order',
        'view_count',
        'published_at',
    ];

    protected $casts = [
        'materials' => 'array',
        'care_instructions' => 'array',
        'size_chart' => 'array',
        'specs' => 'array',
        'features' => 'array',
        'colors' => 'array',
        'sort_order' => 'int',
        'view_count' => 'int',
        'is_active' => 'bool',
        'is_featured' => 'bool',
        'is_new' => 'bool',
        'is_bestseller' => 'bool',
        'has_ca' => 'bool',
        'published_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'is_active', 'is_featured'])
            ->logOnlyDirty()
            ->useLogName('catalog:product');
    }

    /** @return BelongsTo<Category, self> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return BelongsTo<Brand, self> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** @return BelongsTo<Collection, self> */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /** @return HasMany<ProductImage, self> */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('cover')->singleFile();
    }
}
