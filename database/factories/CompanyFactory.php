<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
final class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();

        return [
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'name' => $name,
            'legal_name' => $this->faker->company().' S.A.',
            'document' => $this->faker->numerify('##.###.###/####-##'),
            'slogan' => 'Tradição, qualidade e conforto.',
            'about' => 'Empresa referência em calçados premium.',
            'email_primary' => $this->faker->safeEmail(),
            'phone_primary' => '+55 35 98811-9922',
            'whatsapp_number' => '5535988119922',
            'theme_color' => '#0a0a0a',
            'dark_mode_default' => false,
            'is_active' => true,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
