<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebRequest;
use App\Http\Requests\UpdateWebRequest;
use App\Models\Web;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        try {
            $web = Web::create([
                'name' => $request->input('name'),
                'url' => $request->input('url'),
                'user_id' => auth()->id(),
                'design_config' => $request->input('design_config', []),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = auth()->user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Debes iniciar sesión primero.');
            }

            $user->webs()->attach($web->id);

            return redirect()->route('admin.webs.show', $web)
                             ->with('success', 'Web creada y vinculada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error en WebController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
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
        // Verificar que el usuario tenga acceso a esta web
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes acceso a esta web.');
        }

        return view('cliente.webs.edit', compact('web'));
    }

    /**
     * Mostrar página de diseño inicial con datos existentes
     */
    public function editDesign(Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('cliente.diseno', compact('web'));
    }

    /**
     * Mostrar página de bienvenida con datos existentes
     */
    public function editWelcome(Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('cliente.bienvenida', compact('web'));
    }

    /**
     * Mostrar página principal con datos existentes
     */
    public function editMain(Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('cliente.principal', compact('web'));
    }

    /**
     * Mostrar página de contacto con datos existentes
     */
    public function editContact(Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('cliente.contacto', compact('web'));
    }

    /**
     * Actualizar configuración de diseño
     */
    public function updateDesignConfig(Request $request, Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        try {
            $web->design_config = [
                'welcomeMessage' => $request->boolean('welcomeMessage'),
                'contactPage' => $request->boolean('contactPage')
            ];
            $web->save();

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en updateDesignConfig: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la configuración'
            ], 500);
        }
    }

    /**
     * Actualizar datos de página de bienvenida
     */
    public function updateWelcomePage(Request $request, Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        try {
            $data = $request->except(['_token']);
            
            // Manejar upload de archivos
            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe
                if (isset($web->welcome_page_data['logo'])) {
                    Storage::disk('public')->delete($web->welcome_page_data['logo']);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo_path'] = $logoPath;
            }
            
            if ($request->hasFile('background_image')) {
                // Eliminar imagen anterior si existe
                if (isset($web->welcome_page_data['background_image'])) {
                    Storage::disk('public')->delete($web->welcome_page_data['background_image']);
                }
                $bgPath = $request->file('background_image')->store('backgrounds', 'public');
                $data['background_image_path'] = $bgPath;
            }

            $web->welcome_page_data = $data;
            $web->save();

            return response()->json([
                'success' => true,
                'message' => 'Página de bienvenida actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en updateWelcomePage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la página de bienvenida'
            ], 500);
        }
    }

    /**
     * Actualizar datos de página principal
     */
    public function updateMainPage(Request $request, Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        try {
            $data = $request->except(['_token']);
            
            // Manejar upload de archivos
            if ($request->hasFile('logo')) {
                if (isset($web->main_page_data['logo'])) {
                    Storage::disk('public')->delete($web->main_page_data['logo']);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
                $data['logo_path'] = $logoPath;
            }
            
            if ($request->hasFile('main_photo')) {
                if (isset($web->main_page_data['main_photo'])) {
                    Storage::disk('public')->delete($web->main_page_data['main_photo']);
                }
                $photoPath = $request->file('main_photo')->store('photos', 'public');
                $data['main_photo'] = $photoPath;
            }

            $web->main_page_data = $data;
            $web->save();

            return response()->json([
                'success' => true,
                'message' => 'Página principal actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en updateMainPage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la página principal'
            ], 500);
        }
    }

    /**
     * Actualizar datos de página de contacto
     */
    public function updateContactPage(Request $request, Web $web)
    {
        if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        try {
            $data = $request->except(['_token']);
            $web->contact_page_data = $data;
            $web->save();

            return response()->json([
                'success' => true,
                'message' => 'Página de contacto actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en updateContactPage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la página de contacto'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebRequest $request, Web $web)
    {
        try {
            $web->name = $request->input('name');
            $web->url = $request->input('url');
            $web->save();

            return redirect()->route('admin.webs.show', $web)
                             ->with('success', 'Web actualizada correctamente.');

        } catch (\Exception $e) {
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
        try {
            // Verificar permisos
            if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
                return redirect()->back()->with('error', 'No tienes permisos para eliminar esta web.');
            }

            // Eliminar archivos asociados
            if ($web->welcome_page_data && isset($web->welcome_page_data['logo'])) {
                Storage::disk('public')->delete($web->welcome_page_data['logo']);
            }
            if ($web->welcome_page_data && isset($web->welcome_page_data['background_image'])) {
                Storage::disk('public')->delete($web->welcome_page_data['background_image']);
            }
            if ($web->main_page_data && isset($web->main_page_data['logo'])) {
                Storage::disk('public')->delete($web->main_page_data['logo']);
            }
            if ($web->main_page_data && isset($web->main_page_data['main_photo'])) {
                Storage::disk('public')->delete($web->main_page_data['main_photo']);
            }

            // Eliminar directorio de la web publicada si existe
            $webPath = public_path('webs/' . $web->url);
            if (is_dir($webPath)) {
                $this->deleteDirectory($webPath);
            }

            $web->delete();

            return redirect()->route('perfil')
                             ->with('success', 'Web eliminada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error eliminando web: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Ups... Parece que el servidor está ocupado. Por favor, vuelve a intentarlo en unos minutos.');
        }
    }

    /**
     * Función auxiliar para eliminar directorios recursivamente
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) return false;
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        return rmdir($dir);
    }

    public function perfil()
    {
        $user = Auth::user();
        $webs = $user->webs;
        return view('perfil.perfil', compact('webs'));
    }

    public function home()
    {
        $webs = Web::where('is_published', true)->get();
        return view('welcome', compact('webs'));
    }
}