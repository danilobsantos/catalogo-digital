<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simula contexto de login
use App\Models\User;
use App\Domains\Catalog\Models\Product;
use Livewire\Livewire;

$user = User::first();
auth()->login($user);

$product = Product::first();
echo "Testing with Product ID: " . $product->id . "\n";
echo "Product Code: " . $product->code . "\n";

try {
    $component = Livewire::test(\App\Livewire\Admin\Products\Form::class, ['product' => $product->id]);
    echo "Component Code after mount: " . $component->get('code') . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
