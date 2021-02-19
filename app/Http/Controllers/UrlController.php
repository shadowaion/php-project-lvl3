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
             ->select(DB::raw('*'))
             ->orderby('name')
             ->get();

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
        $normalizedScheme = mb_strtolower($parsedUrl["scheme"]);
        $normalizedHost = mb_strtolower($parsedUrl["host"]);
        $normalizedUrlName = "{$normalizedScheme}://{$normalizedHost}/";
        $createdUpdatedAt = Carbon::now()->toDateTimeString();

        DB::table('urls')
        ->where('name', $normalizedUrlName)
        ->upsert([
            ['name' => $normalizedUrlName,
            'updated_at' => $createdUpdatedAt, 
            'created_at' => $createdUpdatedAt],
        ], ['name'], ['updated_at']);

        return Redirect()->route('urlsList');//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('welcome');//
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
}
