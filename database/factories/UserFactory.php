<?php

namespace Database\Factories;

use Faker\Provider\bg_BG\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $full_name = fake()->name();
        $username = explode(" ", $full_name);

        $genarateName = '';
        if (count($username) > 0) {
            $genarateName .= $username[0];
        }
        if (count($username) > 1) {
            $genarateName .= $username[1];
        }
        $user_sponser_name = strtolower($genarateName . random_int(0, 999));
        return [
            'full_name' =>  $full_name,
            'user_name' => $user_sponser_name,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'sponserId' => "hafijulislam193",
            'income_balance' => random_int(100, 500),
            'phone' => fake()->phoneNumber(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
