<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebRequest;
use App\Http\Requests\UpdateWebRequest;
use App\Models\Web;

class WebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $webs = Web::all(); 
        return view('admin.webs.index', compact('webs')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Web $web)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Web $web)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebRequest $request, Web $web)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Web $web)
    {
        //
    }
}
