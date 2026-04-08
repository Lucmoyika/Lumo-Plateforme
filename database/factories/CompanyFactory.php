<?php
namespace Database\Factories;
use App\Modules\Entreprises\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
class CompanyFactory extends Factory {
    protected $model = Company::class;
    public function definition(): array {
        return ['name' => $this->faker->company(), 'sector' => $this->faker->word(), 'city' => $this->faker->city(), 'email' => $this->faker->unique()->safeEmail(), 'phone' => $this->faker->phoneNumber(), 'status' => 'active', 'size' => '10-50'];
    }
}
