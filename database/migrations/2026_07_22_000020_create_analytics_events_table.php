<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            $table->string('event', 32);
            // event in:  view, search, whatsapp, banner_click, category_click
            $table->jsonb('payload')->nullable();
            $table->string('session_id', 64)->nullable()->index();
            $table->string('path', 191)->nullable();
            $table->string('referrer')->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('occurred_at')->useCurrent();

            $table->index(['company_id', 'event', 'occurred_at']);
            $table->index(['event', 'payload']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
