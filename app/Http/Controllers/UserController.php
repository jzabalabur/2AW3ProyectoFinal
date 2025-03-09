<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); 
        return view('admin.users.index', compact('users')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try{
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        
        return redirect()->route('admin.users.show', $user)
                         ->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e){
            return redirect()->back()
            ->withInput() 
            ->with('error', 'Ups... Parece que el servidor está ocupado. Por favor, vuelve a intentarlo en unos minutos.'); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try{
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Si se proporciona una nueva contraseña, se actualiza
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('admin.users.show', $user)
                         ->with('success', 'Usuario actualizado correctamente.');

        } catch (\Exception $e){
            return redirect()->back()
            ->withInput() 
            ->with('error', 'Ups... Parece que el servidor está ocupado. Por favor, vuelve a intentarlo en unos minutos.'); 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try{
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e){
            return redirect()->back()
            ->with('error', 'Ups... Parece que el servidor está ocupado. Por favor, vuelve a intentarlo en unos minutos.'); 
        }      
    }
}
