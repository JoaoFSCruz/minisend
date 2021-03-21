<?php

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Email::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender' => $this->faker->email,
            'recipient' => $this->faker->email,
            'subject' => $this->faker->words(3, true),
            'text' => $this->faker->text,
            'html' => $this->faker->randomHtml(1, 1),
        ];
    }

    /**
     * Set the email as 'sent'.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sent()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'sent'
            ];
        });
    }

    /**
     * Set the email as 'failed'.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed'
            ];
        });
    }
}
