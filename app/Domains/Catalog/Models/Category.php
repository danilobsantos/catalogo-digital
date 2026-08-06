<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Models;

use App\Traits\HasCompanyScope;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Categoria de produtos (Botina, Coturno, Bota, Passeio, Segurança, etc.).
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 */
class Category extends Model
{
    use HasCompanyScope;

    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    use LogsActivity;

    protected $fillable = [
        'company_id',
        'parent_id',
        'slug',
        'name',
        'description',
        'cover_path',
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
            ->logOnly(['slug', 'name', 'is_active', 'is_featured'])
            ->logOnlyDirty()
            ->useLogName('catalog:category');
    }

    /** @return BelongsTo<Category, self> */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** @return HasMany<Product, self> */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
