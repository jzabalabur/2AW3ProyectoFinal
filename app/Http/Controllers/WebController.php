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
        return view('admin.webs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebRequest $request)
    {
        try{
            $web = Web::create([
                'name' => $request->input('name'),
                'url' => $request->input('url'),
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            
            return redirect()->route('admin.webs.show', $web)
                             ->with('success', 'Web creada correctamente.');
            } catch (\Exception $e) {
                // Registra el error exacto en el log
                \Log::error('Error en WebController: ' . $e->getMessage(), [
                    'exception' => $e
                ]);
            
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ups... Parece que el servidor está ocupado. Por favor, vuelve a intentarlo en unos minutos.');
            }
            
    }

    /**
     * Display the specified resource.
     */
    public function show(Web $web)
    {
        return view('admin.webs.show', compact('web'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Web $web)
    {
        return view('admin.webs.edit', compact('web'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebRequest $request, Web $web)
    {
        try{
            $web->name = $request->input('name');
            $web->url = $request->input('url');
    
            $web->save();
    
            return redirect()->route('admin.webs.show', $web)
                             ->with('success', 'Web actualizada correctamente.');
    
            } catch (\Exception $e){
                return redirect()->back()
                ->withInput() 
                ->with('error', 'Ups... Parece que el servidor está ocupado. Por favor, vuelve a intentarlo en unos minutos.'); 
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Web $web)
    {
        try{
            $web->delete();
    
            return redirect()->route('admin.webs.index')
                             ->with('success', 'Web eliminada correctamente.');
            } catch (\Exception $e){
                return redirect()->back()
                ->with('error', 'Ups... Parece que el servidor está ocupado. Por favor, vuelve a intentarlo en unos minutos.'); 
            }  ;
    
    }
}
