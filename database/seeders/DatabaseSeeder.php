<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Url;
use App\Models\UrlCheck;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Url::factory()->create([
            'id' => 1,
            'name' => 'https://example.com'
        ]);
        UrlCheck::factory()->create([
            'url_id' => 1,
        ]);
        UrlCheck::factory()->create([
            'url_id' => 1,
        ]);
    }
}
