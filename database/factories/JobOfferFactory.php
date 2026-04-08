<?php
namespace Database\Factories;
use App\Modules\Emploi\Models\JobOffer;
use Illuminate\Database\Eloquent\Factories\Factory;
class JobOfferFactory extends Factory {
    protected $model = JobOffer::class;
    public function definition(): array {
        return ['title' => $this->faker->jobTitle(), 'description' => $this->faker->paragraph(), 'type' => 'CDI', 'location' => $this->faker->city(), 'currency' => 'FCFA', 'status' => 'active', 'remote' => false];
    }
}
