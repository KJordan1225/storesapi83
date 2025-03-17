<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserProfile;
use Illuminate\Support\Str;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;
    
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstname,
            'last_name' => $this->faker->lastname,
            'address1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'zip_code' => $this->faker->postcode,
            'dob' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'queversary' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'phone_number' => $this->faker->phoneNumber, 
            'phone_type' => $this->faker->randomElement ( ['mobile', 'landline' ] ),
        ];
    }
}
