<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $verifiedAt = [null, now()];
    $faker = fake('ru_RU');

    return [
      'name' => $faker->name(),
      'email' => $faker->unique()->safeEmail(),
      'email_verified_at' => fake()->randomElement($verifiedAt),
      'password' => static::$password ??= Hash::make('password'),
      'phone_number' => $faker->phoneNumber(),
      'location' => $faker->city(),
      'street' => $faker->streetName(),
      'house' => $faker->streetAddress(),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn(array $attributes) => [
      'email_verified_at' => null,
    ]);
  }
}