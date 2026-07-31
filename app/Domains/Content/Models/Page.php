<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use App\Traits\HasCompanyScope;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Página institucional (single-page-friendly content).
 */
class Page extends Model
{
    use HasCompanyScope;

    /** @use HasFactory<PageFactory> */
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'company_id',
        'slug',
        'title',
        'subtitle',
        'content',
        'meta_title',
        'meta_description',
        'cover_path',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'sort_order' => 'int',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'title', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('content:page');
    }
}
