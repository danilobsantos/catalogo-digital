<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Models;

use App\Traits\HasCompanyScope;
use Database\Factories\ProductImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Imagem avulsa de produto (path absoluto em storage).
 *
 * @property int $id
 * @property int $product_id
 * @property string $path
 */
class ProductImage extends Model
{
    use HasCompanyScope;

    /** @use HasFactory<ProductImageFactory> */
    use HasFactory;

    use LogsActivity;

    protected $fillable = [
        'company_id',
        'product_id',
        'path',
        'thumb_path',
        'cover_path',
        'disk',
        'alt_text',
        'caption',
        'is_cover',
        'sort_order',
        'dimensions',
        'size_bytes',
        'mime_type',
    ];

    protected $casts = [
        'is_cover' => 'bool',
        'sort_order' => 'int',
        'dimensions' => 'array',
        'size_bytes' => 'int',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'is_cover'])
            ->logOnlyDirty()
            ->useLogName('catalog:product_image');
    }

    /** @return BelongsTo<Product, self> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
