<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\Web;



class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Web $web)
    {   
        $pages = $web->pages;
        return view('admin.pages.index', compact('web', 'pages')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Web $web)
    {
        return view('admin.pages.create', compact('web'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Web $web, Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Web $web, Page $page)
    {
        return view('admin.pages.show', compact('web', 'page'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Web $web, Page $page)
    {
        return view('admin.pages.edit', compact('web', 'page'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Web $web, Page $page, Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Web $web, Page $page)
    {
        //
    }
}
