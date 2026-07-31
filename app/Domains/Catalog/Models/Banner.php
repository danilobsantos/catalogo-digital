<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Models;

use App\Traits\HasCompanyScope;
use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Banner rotativo do catálogo (hero, mid, footer).
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 */
class Banner extends Model implements HasMedia
{
    use HasCompanyScope;

    /** @use HasFactory<BannerFactory> */
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'company_id',
        'slug',
        'title',
        'subtitle',
        'description',
        'image_path',
        'image_alt',
        'cta_label',
        'cta_url',
        'cta_route_name',
        'position',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'sort_order' => 'int',
        'is_active' => 'bool',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'title', 'is_active', 'position'])
            ->logOnlyDirty()
            ->useLogName('catalog:banner');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
    }

    public function isVisible(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at !== null && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at !== null && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }
}
