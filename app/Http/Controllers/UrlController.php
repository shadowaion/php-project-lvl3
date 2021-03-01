<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Carbon\Carbon;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $urls = DB::table('urls')
        //     ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
        //     ->select(DB::raw('DISCTINCT ON (url.name) urls.*,
        //     url_checks.created_at as last_check_date, url_checks.status_code as status'))
        //     ->orderby('urls.name')
        //     ->orderby('urls.id')
        //     ->orderby('url_checks.created_at', 'desc')
        //     ->get();
        $urls = DB::table('urls')
            ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
            ->select(DB::raw('urls.id, urls.name, 
            MAX(url_checks.created_at) as last_check_date, MAX(url_checks.status_code) as status'))
            ->groupby('urls.id')
            ->orderby('urls.name')
            ->orderby('urls.id')
            ->orderByRaw('MAX(url_checks.created_at) DESC')
            ->get();
        //echo "\n------------------urlsCont var_dump-------------------\n";
        //var_dump($urls);
        return view('urls-index', ['urls' => $urls]);//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('welcome');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo "\n-----------Store 1------------\n";

        $parsedUrl = parse_url($request->input('url.name'));

        echo "\n-----------Store 2------------\n";

        $normalizedScheme = mb_strtolower($parsedUrl["scheme"]);
        $normalizedHost = mb_strtolower($parsedUrl["host"]);
        $normalizedUrlName = "{$normalizedScheme}://{$normalizedHost}";
        $createdUpdatedAt = Carbon::now()->toDateTimeString();

        echo "\n-----------Store 3 {$normalizedUrlName}------------\n";

        try {
            echo "\n-----------Store 4------------\n";

            DB::table('urls')
            ->where('name', '=', $normalizedUrlName)
            ->upsert([
                ['name' => $normalizedUrlName,
                'updated_at' => $createdUpdatedAt,
                'created_at' => $createdUpdatedAt],
            ], ['name'], ['updated_at']);

            echo "\n-----------Store 5------------\n";

            flash('Website successfully added!')->success();

            echo "\n-----------Store 6------------\n";
        } catch (Exception $e) {
            echo "\n-----------Store 7------------\n";

            $errorMessage = "Error: {$e->getMessage()}";
            flash($errorMessage)->error();
        }
        echo "\n-----------Store 8------------\n";

        return Redirect()->route('urls.index');//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $urls = DB::table('urls')
            //->select(DB::raw('*'))
            ->where('id', '=', $id)
            ->orderby('name')
            ->get();

        $urlChecks = DB::table('url_checks')
            //->select(DB::raw('*'))
            ->where('url_id', '=', $id)
            ->orderby('created_at', 'desc')
            ->get();
        
        // echo "\n-----------urls------------\n";
        // var_dump($urls);
        // echo "\n-----------urlChecks------------\n";
        // var_dump($urlChecks);
        
        return view('urls-show', ['urls' => $urls, 'urlChecks' => $urlChecks]);//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return view('welcome');//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return view('welcome');//
    }
    /**
     * Check the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check($id)
    {
        $h1Text = '';
        $keywordsContent = '';
        $descriptionContent = '';

        echo "\n------------------Check 1-------------------\n";

        $createdUpdatedAt = Carbon::now()->toDateTimeString();

        echo "\n------------------Check 2-------------------\n";

        $urls = DB::table('urls')
                ->select(DB::raw('*'))
                ->where('id', '=', $id)
                ->orderby('name')
                ->get();

        echo "\n------------------Check 3-------------------\n";

        $response = Http::get($urls[0]->name);
        $respStatusCode = $response->getStatusCode();

        echo "\n------------------Check 4------------------\n";

        $document = new Document();
        $document->loadHtmlFile($urls[0]->name);

        echo "\n------------------Check 5-------------------\n";

        $h1 = $document->find('h1');
        $keywords = $document->find('meta[name=keywords]');
        $description = $document->find('meta[name=description]');

        echo "\n------------------Check 6-------------------\n";

        if (count($h1) > 0) {
            $h1Text = $h1[0]->innerHtml();
        }
        if (count($keywords) > 0) {
            $keywordsContent = $keywords[0]->getAttribute('content');
        }
        if (count($description) > 0) {
            $descriptionContent = $description[0]->getAttribute('content');
        }

        echo "\n------------------Check 7 {$id}-------------------\n";

        try {
            echo "\n------------------Check 8-------------------\n";
            
            DB::table('url_checks')
            ->upsert([
                ['url_id' => $id,
                'status_code' => $respStatusCode,
                'h1' => $h1Text,
                'keywords' => $keywordsContent,
                'description' => $descriptionContent,
                'updated_at' => $createdUpdatedAt,
                'created_at' => $createdUpdatedAt],
            ], ['id'], ['updated_at']);

            flash('Finished check!')->success();

            echo "\n------------------Check 9-------------------\n";
        } catch (Exception $e) {
            echo "\n------------------Check 10-------------------\n";
            $errorMessage = "Error: {$e->getMessage()}";
            flash($errorMessage)->error();
            echo "\n------------------Check 11-------------------\n";
        }

        echo "\n------------------Check 12-------------------\n";

        return Redirect()->route('urls.show', ['id' => $id]);//
    }
}
