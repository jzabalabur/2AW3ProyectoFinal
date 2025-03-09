<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\UserController;



Route::get('/', function () {
    return view('welcome');
})->name('home');

//Route::get('/dashboard', function () {
//   return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//-----INICIO Rutas Admin-----//
//Dashboard
 Route::get('/admin', function () {
     return view('admin.dashboard');
 })->middleware('auth')->name('admin.dashboard');
//Webs
 Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('webs', WebController::class);
});
//Users
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});
//-----FIN Rutas Admin-----//


//-----INICIO Rutas Cliente-----//
Route::get('/perfil', function () {
    return view('cliente.perfil');
})->name('perfil');

// Ruta para la página de diseño
Route::get('/diseno', function () {
    return view('cliente.diseno'); 
})->name('diseno');

//-----FIN Rutas Cliente-----//



require __DIR__.'/auth.php';
