<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Simula contexto de login
use App\Domains\Catalog\Models\Product;
use App\Livewire\Admin\Products\Form;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Livewire\Livewire;

$user = User::first();
auth()->login($user);

$product = Product::first();
echo 'Testing with Product ID: '.$product->id."\n";
echo 'Product Code: '.$product->code."\n";

try {
    $component = Livewire::test(Form::class, ['product' => $product->id]);
    echo 'Component Code after mount: '.$component->get('code')."\n";
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
