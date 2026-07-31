<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Catalog\Models\Product;
use App\Domains\Company\Models\Company;
use App\Traits\CompanyContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $code = (string) $this->faker->unique()->numberBetween(1000, 6999);
        $name = 'Botina '.$this->faker->words(2, true);

        return [
            'company_id' => CompanyContext::id() ?? Company::factory(),
            'category_id' => null,
            'brand_id' => null,
            'collection_id' => null,
            'code' => $code,
            'variant_code' => null,
            'slug' => Str::slug($name).'-'.$code,
            'name' => $name,
            'subtitle' => null,
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'materials' => ['COURO VAQUETA', 'SOLA DE BORRACHA'],
            'care_instructions' => [
                'Limpar com pano úmido.',
                'Secar à sombra.',
                'Não lavar em máquina.',
            ],
            'size_chart' => [
                '35' => '23cm',
                '36' => '23,5cm',
                '37' => '24cm',
                '38' => '24,5cm',
                '39' => '25cm',
                '40' => '26,5cm',
                '41' => '27cm',
                '42' => '28cm',
                '43' => '29cm',
            ],
            'specs' => ['origem' => 'Nacional'],
            'features' => ['Durabilidade', 'Conforto'],
            'colors' => ['PRETO', 'CAFÉ'],
            'sole' => 'Borracha',
            'leather' => 'Vaqueta',
            'closure' => 'Elástico coberto',
            'toe_cap' => 'PVC',
            'approvals' => null,
            'weight_grams' => '700',
            'has_ca' => false,
            'ca_number' => null,
            'ca_validity' => null,
            'is_active' => true,
            'is_featured' => false,
            'is_new' => false,
            'is_bestseller' => false,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'view_count' => 0,
            'published_at' => now(),
        ];
    }

    public function withCa(?string $number = null): self
    {
        return $this->state([
            'has_ca' => true,
            'ca_number' => $number ?? (string) $this->faker->numberBetween(10000, 60000),
            'ca_validity' => now()->addYears(2)->format('Y-m-d'),
        ]);
    }

    public function featured(): self
    {
        return $this->state(['is_featured' => true]);
    }

    public function newArrival(): self
    {
        return $this->state(['is_new' => true]);
    }
}
