<?php

namespace Database\Factories;

use App\Models\UrlCheck;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class UrlCheckFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UrlCheck::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $word = $this->faker->word();
        $number = $this->faker->randomDigit();
        $createdUpdatedAt = Carbon::now()->toDateTimeString();
        return [
            'url_id' => $number,
            'status_code' => 200,
            'h1' => $word,
            'keywords' => $word,
            'description' => $word,
            'created_at' => $createdUpdatedAt,
            'updated_at' => $createdUpdatedAt
            //
        ];
    }
}
