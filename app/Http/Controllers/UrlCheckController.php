<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Carbon\Carbon;

class UrlCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        return view('welcome');//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\View\View
     */
    public function store(Request $request, int $id)
    {
        //echo "\n------------------Check 0-------------------\n";

        $h1Text = '';
        $keywordsContent = '';
        $descriptionContent = '';

        //echo "\n------------------Check 0.1-------------------\n";

        //echo "\n------------------Check 1-------------------\n";

        $createdUpdatedAt = Carbon::now()->toDateTimeString();

        //echo "\n------------------Check 2-------------------\n";

        $urls = DB::table('urls')
                ->select(DB::raw('*'))
                ->where('id', '=', $id)
                ->orderBy('name')
                ->get();

        //echo "\n------------------Check 3-------------------\n";
        if (config('app.env') == 'testing') {
            //echo "\n------------------Check 0.2-------------------\n";
            Http::fake();
        }

        Http::fake();
        $response = Http::get($urls[0]->name);
        $respStatusCode = $response->getStatusCode();

        //dd($response);
        //echo "\n------------------Check 4------------------\n";
        $body = $response->getBody();
        $content = $body->getContents();
        if ($content === '') {
            $content = '<div></div>';
        }
        //echo "||||=={$content}==|||||";
        //dd($content);
        $document = new Document($content);
        //$document->loadHtmlFile($urls[0]->name);

        //var_dump($urls[0]->name);
        //dd($document);
        //echo "\n------------------Check 5-------------------\n";

        $h1 = $document->find('h1');
        $keywords = $document->find('meta[name=keywords]');
        $description = $document->find('meta[name=description]');

        //echo "\n------------------Check 6-------------------\n";

        if (count($h1) > 0) {
            $h1Text = strip_tags($h1[0]->innerHtml());
        }
        if (count($keywords) > 0) {
            $keywordsContent = $keywords[0]->getAttribute('content');
        }
        if (count($description) > 0) {
            $descriptionContent = $description[0]->getAttribute('content');
        }
        //echo "\n------------------h1 = {$h1Text}-------------------\n";
        //echo "\n------------------keys = {$keywordsContent}-------------------\n";
        //echo "\n------------------decr = {$descriptionContent}-------------------\n";

        //echo "\n------------------Check 7 {$id}-------------------\n";

        try {
            //echo "\n------------------Check 8-------------------\n";

            DB::table('url_checks')
            ->upsert([
                ['url_id' => $id,
                'status_code' => $respStatusCode,
                'keywords' => $keywordsContent,
                'description' => $descriptionContent,
                'h1' => $h1Text,
                'updated_at' => $createdUpdatedAt,
                'created_at' => $createdUpdatedAt],
            ], ['id'], ['updated_at']);

            flash('Finished check!')->success();

            //echo "\n------------------Check 9-------------------\n";
        } catch (Exception $e) {
            //echo "\n------------------Check 10-------------------\n";
            $errorMessage = "Error: {$e->getMessage()}";
            flash($errorMessage)->error();
            //echo "\n------------------Check 11-------------------\n";
        }

        //echo "\n------------------Check 12-------------------\n";

        return redirect()->route('urls.show', ['id' => $id]);//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        return view('welcome');//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        return view('welcome');//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        return view('welcome');//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Illuminate\View\View
     */
    public function destroy($id)
    {
        return view('welcome');//
    }
}
