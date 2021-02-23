<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
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
        $urls = DB::table('urls')
            ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
            ->select(DB::raw('DISTINCT ON (urls.name) urls.*, url_checks.created_at as last_check_date'))
            ->orderby('urls.name')
            ->orderby('urls.id')
            ->orderby('url_checks.created_at', 'desc')
            ->get();
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
        $parsedUrl = parse_url($request->input('url.name'));

        // echo "\n--------------------Inside controller----------------------\n";
        // print_r($request->input('url.name'));
        // echo "\n--------------------Inside controller----------------------\n";
        // print_r($request->name);

        $normalizedScheme = mb_strtolower($parsedUrl["scheme"]);
        $normalizedHost = mb_strtolower($parsedUrl["host"]);
        $normalizedUrlName = "{$normalizedScheme}://{$normalizedHost}";
        $createdUpdatedAt = Carbon::now()->toDateTimeString();

        try {
            DB::table('urls')
            ->where('name', '=', $normalizedUrlName)
            ->upsert([
                ['name' => $normalizedUrlName,
                'updated_at' => $createdUpdatedAt, 
                'created_at' => $createdUpdatedAt],
            ], ['name'], ['updated_at']);

            flash('Website successfully added!')->success();
        } catch (Exception $e) {
            $errorMessage = "Error: {$e->getMessage()}";
            flash($errorMessage)->error();
        }

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
            ->select(DB::raw('*'))
            ->where('id', '=', $id)
            ->orderby('name')
            ->get();
        
        $urlChecks = DB::table('url_checks')
            ->select(DB::raw('*'))
            ->where('url_id', '=', $id)
            ->orderby('created_at', 'desc')
            ->get();
        //var_dump($urls);
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
        $createdUpdatedAt = Carbon::now()->toDateTimeString();

        $urls = DB::table('urls')
                ->select(DB::raw('*'))
                ->where('id', '=', $id)
                ->orderby('name')
                ->get();

        try {
            DB::table('url_checks')
            ->upsert([
                ['url_id' => $id,
                'updated_at' => $createdUpdatedAt, 
                'created_at' => $createdUpdatedAt],
            ], ['id'], ['updated_at']);

            flash('Finished check!')->success();
        } catch (Exception $e) {
            $errorMessage = "Error: {$e->getMessage()}";
            flash($errorMessage)->error();
        }
    
        return Redirect()->route('urls.show', ['id' => $id]);//
    }
}
