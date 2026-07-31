<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Domínio principal (multi-tenant-ready)
    |--------------------------------------------------------------------------
    | Usado como prefixo em nomes canônicos (sitemap, og, s3 keys).
    */
    'company' => [
        'name' => env('CATALOG_COMPANY_DEFAULT', 'CJ Calçados'),
        'domain' => env('APP_DOMAIN', 'cjcalcados.com.br'),
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp (link comercial principal)
    |--------------------------------------------------------------------------
    | Placeholders: {produto}, {codigo}, {url}
    */
    'whatsapp' => [
        'number' => env('WHATSAPP_DEFAULT_NUMBER', '5535988160553'),
        'message' => env(
            'WHATSAPP_DEFAULT_MESSAGE',
            'Olá! Tenho interesse no produto {produto} (cód. {codigo}).'
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mídia (MinIO local / S3 em prod)
    |--------------------------------------------------------------------------
    */
    'media' => [
        'disk' => env('MEDIA_DISK', 'public'),
        'bucket' => env('AWS_BUCKET', 'catalogo'),
        'endpoint' => env('MINIO_PUBLIC_ENDPOINT', 'http://localhost:9000'),
        'public_read_endpoint' => env('MINIO_PUBLIC_ENDPOINT', 'http://localhost:9000'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Contato (destinatário padrão das mensagens do site)
    |--------------------------------------------------------------------------
    */
    'contact' => [
        'recipient' => env('CONTACT_RECIPIENT', 'admin@cjcalcados.com.br'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Caminho absoluto da pasta "material/" dentro do container Sail
    |--------------------------------------------------------------------------
    | Volume read-only mapeado em compose.yaml. Usado pelo seeder/importer
    | para ler os DOCX em `INFORMAÇÕES TÉCNICAS/` e as imagens soltas.
    */
    'material_path' => env('MATERIAL_PATH', '/var/www/material'),

    /*
    |--------------------------------------------------------------------------
    | SEO defaults
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'title_default' => env('SEO_TITLE_DEFAULT', 'Catálogo Digital Premium'),
        'description_default' => env(
            'SEO_DESCRIPTION_DEFAULT',
            'Apresentamos calçados de qualidade premium com curadoria especializada.'
        ),
        'theme_color' => env('THEME_COLOR', '#0a0a0a'),
    ],

    /*
    |--------------------------------------------------------------------------
    | UI
    |--------------------------------------------------------------------------
    */
    'ui' => [
        'items_per_page' => (int) env('CATALOG_ITEMS_PER_PAGE', 12),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback de Company em contexto console (seeds, jobs, scheduler)
    |--------------------------------------------------------------------------
    | Definido dinamicamente pelo BootstrapSeeder ao popular a empresa padrão.
    */
    'console_fallback_company_id' => env('CATALOG_CONSOLE_COMPANY_ID'),
];
