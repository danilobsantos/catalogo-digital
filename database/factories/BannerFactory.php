<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Catalog\Models\Banner;
use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    protected $model = Banner::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = ucfirst($this->faker->words(3, true));

        return [
            'company_id' => CompanyContext::id() ?? Company::factory(),
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(4)),
            'title' => $title,
            'subtitle' => $this->faker->words(4, true),
            'description' => $this->faker->sentence(),
            'image_path' => null,
            'image_alt' => $title,
            'cta_label' => 'Conheça',
            'cta_url' => route('public.products.index', [], false),
            'cta_route_name' => 'public.products.index',
            'position' => 'hero',
            'sort_order' => $this->faker->numberBetween(0, 50),
            'is_active' => true,
            'starts_at' => null,
            'ends_at' => null,
        ];
    }

    public function inactive(): self
    {
        return $this->state(['is_active' => false]);
    }

    public function scheduled(): self
    {
        return $this->state([
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(7),
        ]);
    }
}
