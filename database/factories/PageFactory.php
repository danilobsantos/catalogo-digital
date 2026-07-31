<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Company\Models\Company;
use App\Domains\Content\Models\Page;
use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = ucfirst($this->faker->words(3, true));

        return [
            'company_id' => CompanyContext::id() ?? Company::factory(),
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(4)),
            'title' => $title,
            'subtitle' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(6, true),
            'meta_title' => null,
            'meta_description' => null,
            'cover_path' => null,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
