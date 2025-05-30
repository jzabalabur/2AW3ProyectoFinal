<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicarController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::post('/change-language', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'language' => 'required|in:eu,es'
    ]);
    
    return back()->withCookie(cookie()->forever('language', $request->language));
})->name('language.change');

Route::middleware('auth')->get('/current-user-id', function () {
    return response()->json(['userId' => auth()->id(), 'authenticated' => true]);
});

Route::get('/', [WebController::class, 'home'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::post('/verificar-dominio', [PublicarController::class, 'checkDomain']);
    Route::post('/publicar-pagina', [PublicarController::class, 'publish']);
});

// Perfil y opciones generales autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/perfil/webs/{id}', [UserController::class, 'detachWeb'])->name('perfil.webs.detach');
    Route::post('/webs/{web}/republicar', [PublicarController::class, 'republish'])->name('webs.republish');
    
    // Ruta para verificar el estado de autenticación con más detalles
    Route::get('/auth-status', function () {
        $user = auth()->user();
        return response()->json([
            'authenticated' => !!$user,
            'userId' => $user ? $user->id : null,
            'userName' => $user ? $user->name : null,
            'userEmail' => $user ? $user->email : null
        ]);
    })->name('auth.status');
});

//-----INICIO Rutas Admin-----//
Route::group([
    'middleware' => ['auth', \Spatie\Permission\Middleware\RoleMiddleware::class.':admin'],
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('webs', WebController::class);
    Route::resource('users', UserController::class);
    Route::resource('webs.pages', PageController::class);
});
//-----FIN Rutas Admin-----//

//-----INICIO Rutas Cliente-----//
Route::middleware(['auth'])->group(function () {
    // Dashboard del cliente
    Route::get('/dashboard', [WebController::class, 'home'])->name('cliente.dashboard');
    // Perfil
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    
    // Edición de webs del cliente
    Route::get('/webs/{web}/edit', [WebController::class, 'edit'])->name('webs.edit');
    Route::delete('/webs/{web}', [WebController::class, 'destroy'])->name('webs.destroy');
    
    // Rutas específicas para editar cada página de la web
    Route::get('/webs/{web}/edit-design', [WebController::class, 'editDesign'])->name('webs.edit.design');
    Route::get('/webs/{web}/edit-welcome', [WebController::class, 'editWelcome'])->name('webs.edit.welcome');
    Route::get('/webs/{web}/edit-main', [WebController::class, 'editMain'])->name('webs.edit.main');
    Route::get('/webs/{web}/edit-contact', [WebController::class, 'editContact'])->name('webs.edit.contact');
    
    // APIs para actualizar datos de cada página
    Route::post('/webs/{web}/update-design-config', [WebController::class, 'updateDesignConfig'])->name('webs.update.design');
    Route::post('/webs/{web}/update-welcome', [WebController::class, 'updateWelcomePage'])->name('webs.update.welcome');
    Route::post('/webs/{web}/update-main', [WebController::class, 'updateMainPage'])->name('webs.update.main');
    Route::post('/webs/{web}/update-contact', [WebController::class, 'updateContactPage'])->name('webs.update.contact');
});
//-----FIN Rutas Cliente-----//

// Rutas para la creación de páginas (existentes)
Route::middleware(['auth'])->group(function () {
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
});

Route::post('/guardar-borrador', [PublicarController::class, 'saveDraft'])->middleware('auth');



require __DIR__.'/auth.php';