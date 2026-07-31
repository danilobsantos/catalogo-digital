<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Catalog\Models\Brand;
use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'company_id' => CompanyContext::id() ?? Company::factory(),
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'name' => $name,
            'logo_path' => null,
            'description' => $this->faker->sentence(),
            'website_url' => $this->faker->url(),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
