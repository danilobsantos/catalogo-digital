<?php

use App\Domains\Company\Providers\CompanyDomainServiceProvider;
use App\Domains\SEO\Providers\CatalogServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    CatalogServiceProvider::class,
    CompanyDomainServiceProvider::class,
];
