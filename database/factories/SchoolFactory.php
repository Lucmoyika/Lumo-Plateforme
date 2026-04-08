<?php

namespace Database\Factories;

use App\Modules\Education\Ecoles\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        $types = ['primary', 'middle', 'secondary', 'technical'];
        $cities = ['Abidjan', 'Dakar', 'Lomé', 'Cotonou', 'Ouagadougou', 'Kinshasa', 'Nairobi', 'Lagos', 'Accra'];

        return [
            'name'    => 'École ' . $this->faker->company(),
            'type'    => $this->faker->randomElement($types),
            'address' => $this->faker->streetAddress(),
            'city'    => $this->faker->randomElement($cities),
            'phone'   => '+225 ' . $this->faker->numerify('## ## ## ## ##'),
            'email'   => $this->faker->unique()->safeEmail(),
            'status'  => 'active',
        ];
    }
}
