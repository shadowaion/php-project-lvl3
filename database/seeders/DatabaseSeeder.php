<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Urls;
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
        Urls::factory()->create([
            'id' => 1,
            'name' => 'https://example.org'
        ]);
        UrlCheckFactory::factory()->create([
            'url_id' => 1,
        ]);
        UrlCheckFactory::factory()->create([
            'url_id' => 1,
        ]);
    }
}
