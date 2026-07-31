<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Catalog\Models\Collection as CollectionModel;
use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CollectionModel>
 */
class CollectionFactory extends Factory
{
    protected $model = CollectionModel::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(2, true));

        return [
            'company_id' => CompanyContext::id() ?? Company::factory(),
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'name' => $name,
            'description' => $this->faker->sentence(),
            'cover_path' => null,
            'accent_color' => '#7c3aed',
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
