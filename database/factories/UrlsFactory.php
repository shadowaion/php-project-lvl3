<?php

namespace Database\Factories;

use App\Models\Urls;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class UrlsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Urls::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $word = $this->faker->word();
        $createdUpdatedAt = Carbon::now()->toDateTimeString();
        return [
            'name' => "https://{$word}.com/",
            'created_at' => $createdUpdatedAt,
            'updated_at' => $createdUpdatedAt
            //
        ];
    }
}
