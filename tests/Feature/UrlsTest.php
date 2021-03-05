<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Tests\TestCase;

class UrlsTest extends TestCase
{
    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));

        $response->assertStatus(200)->assertViewHas('urls');

        return;
    }

    public function testStore(): void
    {
        $url = route('urls.index');
        $name1 = 'https://example.com/blog/posts/how-to-test-code';
        $name2 = 'https://example.org/blogs';
        $name3 = 'https://youtube.com';
        $response = $this->post(route('urls.store'), ['url' => ['name' => $name1]]);

        $response->assertRedirect(route('urls.index'));

        return;
    }

    public function testShow(): void
    {
        $id = 1;

        $response = $this->call('GET', route('urls.show', ['id' => $id]));

        $response->assertViewHas('urls');

        return;
    }

    public function testCheck(): void
    {
        $id = 1;

        $response1 = $this->post(route('urls.check', ['id' => $id]));

        $response1->assertRedirect(route('urls.show', ['id' => $id]));

        return;
    }
}
