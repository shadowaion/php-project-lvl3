<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DiDom\Document;
use DiDom\Element;
use Carbon\Carbon;
use DOMElement;
use Exception;

class UrlCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('welcome');//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
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
     * @return RedirectResponse
     */
    public function store(Request $request, int $id)
    {
        $h1Text = '';
        $keywordsContent = '';
        $descriptionContent = '';

        $createdUpdatedAt = Carbon::now()->toDateTimeString();

        $urls = DB::table('urls')
                ->select(DB::raw('*'))
                ->where('id', '=', $id)
                ->orderBy('name')
                ->get();

        if (config('app.env') == 'testing') {
            Http::fake();
        }

        $response = Http::get($urls[0]->name);
        $respStatusCode = $response->getStatusCode();

        $body = $response->getBody();
        $content = $body->getContents();
        if ($content === '') {
            $content = '<div></div>';
        }

        $document = new Document($content);

        $h1 = $document->find('h1');
        $keywords = $document->find('meta[name=keywords]');
        $description = $document->find('meta[name=description]');

        if (count($h1) > 0) {
            /** @phpstan-ignore-next-line */
            $h1Text = $h1[0]->text();
        }
        if (count($keywords) > 0) {
            $keywordsContent = $keywords[0]->getAttribute('content');
        }
        if (count($description) > 0) {
            $descriptionContent = $description[0]->getAttribute('content');
        }
        try {
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
        } catch (Exception $e) {
            $errorMessage = "Error: {$e->getMessage()}";
            flash($errorMessage)->error();
        }

        return redirect()->route('urls.show', ['id' => $id]);//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        return view('welcome');//
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
