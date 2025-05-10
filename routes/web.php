<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;

Route::post('/change-language', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'language' => 'required|in:eu,es'
    ]);
    
    return back()->withCookie(cookie()->forever('language', $request->language));
})->name('language.change');



Route::get('/', [WebController::class, 'home'])->name('home');

//Route::get('/dashboard', function () {
//   return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/verificar-url', [WebController::class, 'verificarUrl']);

// Perfil y opciones generales autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::delete('/perfil/webs/{id}', [UserController::class, 'detachWeb'])->name('perfil.webs.detach');
});

//-----INICIO Rutas Admin-----//
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Webs (admin)
    Route::resource('webs', WebController::class);

    // Usuarios (admin)
    Route::resource('users', UserController::class);

    // Pages de cada web (admin)
    Route::resource('webs.pages', PageController::class);
});
//-----FIN Rutas Admin-----//


//-----INICIO Rutas Cliente-----//
Route::middleware(['auth'])->group(function () {
    // Dashboard del cliente
    Route::get('/dashboard', [WebController::class, 'home'])->name('cliente.dashboard');

    // Perfil
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');

    // Webs del cliente
    Route::get('/webs/{web}/edit', [WebController::class, 'edit'])->name('webs.edit');
    Route::delete('/webs/{web}', [WebController::class, 'destroy'])->name('webs.destroy');
});
//-----FIN Rutas Cliente-----//

// Ruta para la página de diseño
Route::get('/diseno', function () {
    return view('cliente.diseno'); 
})->name('diseno');
Route::get('/diseno-bienvenida', function () {
    return view('cliente.bienvenida'); 
})->name('bienvenida');
Route::get('/diseno-principal', function () {
    return view('cliente.principal'); 
})->name('principal');
Route::get('/diseno-contacto', function () {
    return view('cliente.contacto'); 
})->name('contacto');
Route::get('/diseno-publicar', function () {
    return view('cliente.publicar'); 
})->name('publicar');
//-----FIN Rutas Cliente-----//



require __DIR__.'/auth.php';
