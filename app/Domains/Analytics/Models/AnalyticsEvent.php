<?php

declare(strict_types=1);

namespace App\Domains\Analytics\Models;

use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Evento analítico do site.
 *
 * Eventos suportados:
 *  - view            payload: {product_id, slug}
 *  - search          payload: {q, results_count, query_url}
 *  - whatsapp_click  payload: {product_id, code, origin_path}
 *  - banner_click    payload: {banner_id, position, destination}
 *  - category_click  payload: {category_slug}
 *  - collection_click payload: {collection_slug}
 *
 * @property int $id
 * @property string $event
 */
class AnalyticsEvent extends Model
{
    use HasCompanyScope;

    protected $table = 'analytics_events';

    public const CREATED_AT = 'occurred_at';

    public const UPDATED_AT = null;

    protected $fillable = [
        'company_id',
        'event',
        'payload',
        'session_id',
        'path',
        'referrer',
        'ip',
        'user_agent',
        'occurred_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];
}
