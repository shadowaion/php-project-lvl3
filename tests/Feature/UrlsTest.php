<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        $id = 0;
        $urls = DB::table('urls')
                ->select(DB::raw('*'))
                ->where('name', '=', 'https://example.org')
                ->orderby('name')
                ->get();
        
        if (count($urls) === 0) {
            $urlData = Urls::factory()->create(['name' => 'https://example.org']);
            $id = $urlData->id;
        } else {
            $id = $urls[0]->id;
        }

        $response = $this->call('GET', route('urls.show', ['id' => $id]));

        $response->assertViewHas('urls');
    }
    public function testCheck()
    {
        $urlsName1 = '';
        $id1 = 0;

        $urls = DB::table('urls')
                ->select(DB::raw('*'))
                ->where('name', '=', 'https://example.org')
                ->orderby('name')
                ->get();
        
        if (count($urls) === 0) {
            $urlsData = Urls::factory()->create(['name' => 'https://example.org']);
            $urlsName1 = $urlsData->name;
            $id1 = $urlsData->id;
        } else {
            $urlsName1 = $urls[0]->name;
            $id1 = $urls[0]->id;
        }

        Http::fake();

        $response1 = $this->post(route('urls.check', ['id' => $id1]));
        $responseStatus1 = Http::get($urlsName1)->getStatusCode();

        $this->assertEquals(200, $responseStatus1);
        $response1->assertRedirect(route('urls.show', ['id' => $id1]));
    }
}
