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
    //use DatabaseMigrations;
    //use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $this->seed();
    // }
    public function testIndex()
    {
        $response = $this->get(route('urls.index'));

        // echo "\n------------------TestIndex-------------------\n";
        // echo "\n------------------Response-------------------\n";
        // //var_dump($response);

        // echo "\n------------------DB query-------------------\n";
        // $urls = DB::table('urls')
        //         ->select(DB::raw('*'))
        //         ->orderby('name')
        //         ->get();
        // echo "\n------------------urls var_dump-------------------\n";
        // var_dump($urls);

        $response->assertStatus(200)->assertViewHas('urls');
    }

    public function testStore()
    {
        $url = route('urls.index');
        $name1 = 'https://example.com/blog/posts/how-to-test-code';
        $name2 = 'https://example.org/blogs';
        $name3 = 'https://youtube.com';
        $response = $this->post(route('urls.store'), ['url' => ['name' => $name3]]);
        
        // echo "\n------------------Response store-------------------\n";
        // var_dump($response);
        
        $response->assertRedirect(route('urls.index'));
    }

    public function testShow()
    {
        // echo "\n------------------DB query-------------------\n";
        // $urls = DB::table('urls')
        //         ->select(DB::raw('*'))
        //         ->orderby('name')
        //         ->get();
        // echo "\n------------------urls var_dump-------------------\n";
        // var_dump($urls);

        $id = 1;

        $response = $this->call('GET', route('urls.show', ['id' => $id]));

        $response->assertViewHas('urls');
    }
    public function testCheck()
    {
        $id = 12;

        Http::fake();

        $response1 = $this->post(route('urls.check', ['id' => $id]));

        // echo "\n------------------Response Check-------------------\n";
        // var_dump($response);

        $response1->assertRedirect(route('urls.show', ['id' => $id]));
    }
}
