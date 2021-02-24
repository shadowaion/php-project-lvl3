<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Urls;
use Tests\TestCase;

class UrlsTest extends TestCase
{
    //use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testIndex()
    {
        $response = $this->get(route('urls.index'));

        $response->assertStatus(200)->assertViewHas('urls');
    }

    public function testStore()
    {
        $url = route('urls.index');
        $name1 = 'https://example.org/blog/posts/how-to-test-code';
        $response = $this->post(route('urls.store'), ['url' => ['name' => $name1]]);
        $response->assertRedirect(route('urls.index'));
    }

    public function testShow()
    {
        $urlData = Urls::factory()->create();
        // $urlCheckData = UrlCheck::factory()->create([
        //     'url_id' => $urlData->id
        // ]);
        $response = $this->call('GET', route('urls.show', ['id' => $urlData->id]));

        $response->assertViewHas('urls');
    }
    public function testCheck()
    {
        $urlData = Urls::factory()->create();
        $response = $this->post(route('urls.check', ['id' => $urlData->id]));
        $response->assertRedirect(route('urls.show', ['id' => $urlData->id]));
    }
}
