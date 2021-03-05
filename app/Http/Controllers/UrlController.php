<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DiDom\Document;
use DiDom\Element;
use DOMElement;
use Carbon\Carbon;
use Exception;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $urls = DB::table('urls')
            ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
            ->select(DB::raw('urls.id, urls.name, 
            MAX(url_checks.created_at) as last_check_date, MAX(url_checks.status_code) as status'))
            ->groupBy('urls.id')
            ->orderBy('urls.name')
            ->orderBy('urls.id')
            ->orderByRaw('MAX(url_checks.created_at) DESC')
            ->get();
        return view('urls-index', ['urls' => $urls]);//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
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
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $parsedUrl = parse_url($request->input('url.name'));

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

        return redirect()->route('urls.index');//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $urls = DB::table('urls')
            ->where('id', '=', $id)
            ->orderBy('name')
            ->get();

        $urlChecks = DB::table('url_checks')
            ->where('url_id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('urls-show', ['urls' => $urls, 'urlChecks' => $urlChecks]);//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
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
     * @return View
     */
    public function update(Request $request, $id)
    {
        return view('welcome');//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return View
     */
    public function destroy($id)
    {
        return view('welcome');//
    }
}
