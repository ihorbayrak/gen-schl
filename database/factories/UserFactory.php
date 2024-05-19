<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    private ?string $email = null;

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function definition(): array
    {
        return [
            'email' => $this->email ?? $this->faker->safeEmail(),
        ];
    }
}
