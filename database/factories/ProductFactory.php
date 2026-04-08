<?php
namespace Database\Factories;
use App\Modules\Ecommerce\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProductFactory extends Factory {
    protected $model = Product::class;
    public function definition(): array {
        return ['name' => $this->faker->words(3, true), 'slug' => $this->faker->unique()->slug(), 'description' => $this->faker->paragraph(), 'price' => $this->faker->numberBetween(1000, 100000), 'currency' => 'FCFA', 'stock' => $this->faker->numberBetween(0, 100), 'status' => 'active', 'featured' => false];
    }
}
