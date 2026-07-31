<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductImage;
use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'company_id' => CompanyContext::id() ?? Company::factory(),
            'product_id' => Product::factory(),
            'path' => 'products/'.fake()->uuid().'.jpg',
            'disk' => 'public',
            'alt_text' => fake()->sentence(3),
            'caption' => null,
            'is_cover' => false,
            'sort_order' => fake()->numberBetween(0, 50),
            'dimensions' => ['width' => 1200, 'height' => 900],
            'size_bytes' => fake()->numberBetween(100_000, 6_000_000),
            'mime_type' => 'image/jpeg',
        ];
    }

    public function cover(): self
    {
        return $this->state(['is_cover' => true]);
    }
}
