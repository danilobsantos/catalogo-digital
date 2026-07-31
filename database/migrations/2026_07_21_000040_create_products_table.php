<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('collection_id')->nullable()->constrained('collections')->nullOnDelete();

            $table->string('code', 32);
            $table->string('variant_code', 32)->nullable();
            $table->string('slug');
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->jsonb('materials')->nullable();
            $table->jsonb('care_instructions')->nullable();
            $table->jsonb('size_chart')->nullable();
            $table->jsonb('specs')->nullable();
            $table->jsonb('features')->nullable();
            $table->jsonb('colors')->nullable();

            $table->string('sole')->nullable();
            $table->string('leather')->nullable();
            $table->string('closure')->nullable();
            $table->string('toe_cap')->nullable();
            $table->string('approvals')->nullable();
            $table->string('weight_grams', 16)->nullable();

            $table->boolean('has_ca')->default(false);
            $table->string('ca_number', 64)->nullable();
            $table->string('ca_validity')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_bestseller')->default(false);

            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('view_count')->default(0);

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'slug']);
            $table->unique(['company_id', 'code', 'variant_code']);
            $table->index(['company_id', 'is_active']);
            $table->index(['company_id', 'is_featured']);
            $table->index(['company_id', 'category_id']);
            $table->index(['company_id', 'brand_id']);
            $table->index(['company_id', 'collection_id']);
            $table->index(['company_id', 'is_new']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
