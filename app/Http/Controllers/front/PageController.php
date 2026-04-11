<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->active()->firstOrFail();
        return view('front.pages.show', compact('page'));
    }

    public function index()
    {
        $pages = Page::active()->orderBy('title')->get();
        return view('front.pages.index', compact('pages'));
    }
}
