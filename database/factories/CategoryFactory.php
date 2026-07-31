<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Catalog\Models\Category;
use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(2, true));

        return [
            'company_id' => CompanyContext::id() ?? Company::factory(),
            'parent_id' => null,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'name' => $name,
            'description' => $this->faker->sentence(),
            'cover_path' => null,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function featured(): self
    {
        return $this->state(['is_featured' => true]);
    }

    public function inactive(): self
    {
        return $this->state(['is_active' => false]);
    }
}
