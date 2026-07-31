<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('document', 32)->nullable()->comment('CNPJ/CPF/EIN');
            $table->string('slogan')->nullable();
            $table->text('about')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('email_primary')->nullable();
            $table->string('phone_primary', 32)->nullable();
            $table->string('whatsapp_number', 32)->nullable();
            $table->jsonb('social')->nullable();
            $table->jsonb('address')->nullable();
            $table->string('theme_color', 16)->nullable();
            $table->boolean('dark_mode_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
