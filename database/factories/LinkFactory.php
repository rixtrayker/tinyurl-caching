<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => Str::random(10),
            'link' => 'https://google.com',
        ];
    }


    public function forFirstUser(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::first()->id,
            ];
        });
    }
}
