<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userType = new UserType(['name' => 'Customer']);
        $userType->save();

        return [
            // 'name' => $this->faker->name, //there is no name column, so I need comment this perhaps ?
            'email' => $this->faker->unique()->safeEmail,
            // 'email_verified_at' => now(), //there is no email_verified_at column too
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            // 'remember_token' => Str::random(10),
            'user_type_id' => $userType->id// wonder am I doing it right since I cannot make seeder for user type, I create the one data and give the id for seeders
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
