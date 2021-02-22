<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Urls;
use Tests\TestCase;

class UrlsTest extends TestCase
{
    use RefreshDatabase;
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
        $response = $this->call('GET', 'urls');

        $response->assertViewHas('urls');
    }

    // public function testStore()
    // {
    //     $url = route('urls.index');

    //     $urlData = Urls::factory()->create();

    //     $myRequest = new \Illuminate\Http\Request();
    //     //$myRequest->merge(['url.name' => $urlData->attributesToArray()['name']]);
    //     $myRequest->name = $urlData->attributesToArray()['name'];

    //     // echo "\n--------------------This is all input data----------------------\n";
    //     // print_r($request->all());

    //     // echo "\n--------------------This is whole model----------------------\n";
    //     // print_r($urlData);
    //     // echo "\n----------------------This is ID---------------------\n";
    //     // print_r($urlData->getKey());
    //     // echo "\n----------------------This is attributes---------------------\n";
    //     // print_r($urlData->attributesToArray());
    //     // echo "\n----------------------This is name---------------------\n";
    //     // print_r($urlData->attributesToArray()['name']);
        
    //     $response = $this->post(route('urls.store'));

    //     //$response = $this->get(route('redirectTest'));
    //     //$this->assertEquals(302, $response->getStatusCode());

    //     $response->assertRedirect(route('urls.index'));
    // }

    public function testShow()
    {
        $urlData = Urls::factory()->create();
        $response = $this->call('GET', route('urls.show', ['id' => $urlData->id]));

        $response->assertViewHas('urls');
    }
}
