<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Web;
use App\Models\Page;

class PublicarController extends Controller
{
    public function checkDomain(Request $request)
    {
        $url = $request->input('url');

        if (!$url) {
            return response()->json(['error' => 'URL no proporcionada'], 400);
        }

        // Busca el dominio tal cual se envía
        $existe = Web::where('url', $url)->exists();

        // Devuelve 'disponible' = true si NO existe
        return response()->json(['disponible' => !$existe]);
    }
    
    public function publish(Request $request)
    {
        // Validar el dominio
        $request->validate([
            'url' => 'required|unique:webs,url|regex:/^(?!:\/\/)([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.[a-zA-Z]{2,11}?$/'
        ]);
    
        try {
            $data = $request->all();
            $domain = $request->input('url');
            $user_id = $request->input('user_id');
            $webPath = public_path('webs/' . $domain);
            $name = $this->extractDomainName($domain);
            
            // DEBUG: Log completo de los datos recibidos
            \Log::info('=== DATOS RECIBIDOS EN PUBLISH ===', [
                'domain' => $domain,
                'user_id' => $user_id,
                'data_keys' => array_keys($data),
                'has_images' => isset($data['images']),
                'images_type' => isset($data['images']) ? gettype($data['images']) : 'no_images',
                'mainPageData_exists' => isset($data['mainPageData']),
                'welcomeData_exists' => isset($data['welcomeData']),
                'contactData_exists' => isset($data['contactData'])
            ]);
            
            // Crear directorio para la web
            if (!file_exists($webPath)) {
                mkdir($webPath, 0755, true);
                mkdir($webPath . '/images', 0755, true);
                mkdir($webPath . '/css', 0755, true);
            }
    
            // Obtener el usuario autenticado
            $user = Auth::user();
            if (!$user) {
                \Log::warning('User is null despite auth middleware');
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication error. Please log in again.'
                ], 401);
            }
            
            // Preparar configuración de diseño
            $designConfig = [
                'welcomeMessage' => !empty($data['welcomeData']),
                'contactPage' => !empty($data['contactData'])
            ];
            
            // Preparar datos de páginas
            $welcomePageData = null;
            $mainPageData = null;
            $contactPageData = null;
            
            if (!empty($data['welcomeData'])) {
                $welcomePageData = json_decode($data['welcomeData'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Error decodificando welcomeData: ' . json_last_error_msg());
                    return response()->json(['success' => false, 'message' => 'Error en datos de bienvenida'], 400);
                }
            }
            
            if (!empty($data['mainPageData'])) {
                $mainPageData = json_decode($data['mainPageData'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Error decodificando mainPageData: ' . json_last_error_msg());
                    return response()->json(['success' => false, 'message' => 'Error en datos principales'], 400);
                }
            }
            
            if (!empty($data['contactData'])) {
                $contactPageData = json_decode($data['contactData'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Error decodificando contactData: ' . json_last_error_msg());
                    return response()->json(['success' => false, 'message' => 'Error en datos de contacto'], 400);
                }
            }
            
            // PROCESAMIENTO SEGURO DE IMÁGENES
            $processedImages = [];
            if (isset($data['images'])) {
                \Log::info('Procesando imágenes...', [
                    'images_type' => gettype($data['images']),
                    'images_content' => is_string($data['images']) ? substr($data['images'], 0, 100) . '...' : $data['images']
                ]);
                
                if (is_string($data['images'])) {
                    // Si es string, intentar decodificar JSON
                    $decodedImages = json_decode($data['images'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages)) {
                        $processedImages = $decodedImages;
                        \Log::info('Imágenes decodificadas correctamente', ['count' => count($processedImages)]);
                    } else {
                        \Log::warning('Error decodificando imágenes JSON: ' . json_last_error_msg());
                    }
                } elseif (is_array($data['images'])) {
                    $processedImages = $data['images'];
                    \Log::info('Imágenes ya son array', ['count' => count($processedImages)]);
                } else {
                    \Log::warning('Tipo de imágenes no reconocido: ' . gettype($data['images']));
                }
            } else {
                \Log::info('No se recibieron imágenes');
            }
            
            // Actualizar el array $data con las imágenes procesadas
            $data['images'] = $processedImages;
            
            // Crear registro en la tabla webs
            $web = Web::create([
                'url' => $domain,
                'user_id' => $user_id,
                'name' => $name,
                'design_config' => $designConfig,
                'welcome_page_data' => $welcomePageData,
                'main_page_data' => $mainPageData,
                'contact_page_data' => $contactPageData,
                'is_published' => true,
                'published_at' => now()
            ]);
            
            $webId = $web->id;
            \Log::info('Web creada con ID: ' . $webId);
    
            // Crear registro en la tabla intermedia user_web
            DB::table('user_web')->insert([
                'user_id' => $user_id,
                'web_id' => $webId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            // Procesar páginas con manejo de errores individual
            try {
                \Log::info('Procesando página principal...');
                $this->processMainPage($webId, $data, $webPath);
                \Log::info('Página principal procesada correctamente');
            } catch (\Exception $e) {
                \Log::error('Error procesando página principal: ' . $e->getMessage());
                throw $e;
            }
            
            if (isset($data['welcomeData'])) {
                try {
                    \Log::info('Procesando página de bienvenida...');
                    $this->processWelcomePage($webId, $data, $webPath);
                    \Log::info('Página de bienvenida procesada correctamente');
                } catch (\Exception $e) {
                    \Log::error('Error procesando página de bienvenida: ' . $e->getMessage());
                    throw $e;
                }
            }
            
            if (isset($data['contactData'])) {
                try {
                    \Log::info('Procesando página de contacto...');
                    $this->processContactPage($webId, $data, $webPath);
                    \Log::info('Página de contacto procesada correctamente');
                } catch (\Exception $e) {
                    \Log::error('Error procesando página de contacto: ' . $e->getMessage());
                    throw $e;
                }
            }
    

    
                    //PRUEBAS publicar en apache y bind---------------------------------------------
            try {
        //Configurar Apache
                $webPathApache = "/home/isard/proyectoFinal/public/webs/$domain";

        \Log::info('Llamando script Apache para configurar Virtual Host', compact('domain', 'webPathApache'));
        $apacheCmd = "ssh -i /home/sail/.ssh/id_ed25519 -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null isard@192.168.99.100 /usr/local/bin/configure_apache_vhost.sh " .
                      escapeshellarg($domain) . ' ' . escapeshellarg($webPathApache);

        exec($apacheCmd . ' 2>&1', $apacheOutput, $apacheStatus);

        if ($apacheStatus !== 0) {
            \Log::error("Fallo configurando Apache", [
                'command' => $apacheCmd,
                'output' => $apacheOutput
            ]);
            return response()->json([
                'success' => false,
                'step' => 'apache',
                'message' => "Error al configurar Apache: " . implode("\n", $apacheOutput)
            ], 500);
        }

        \Log::info("Apache configurado correctamente", ['output' => $apacheOutput]);


        //Configurar BIND
        $serverIP = '192.168.99.100'; // o IP del servidor
        \Log::info('Llamando script BIND para configurar zona DNS', compact('domain', 'serverIP'));

        $bindCmd = "ssh -i /home/sail/.ssh/id_ed25519 -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null isard@192.168.99.100 /usr/local/bin/configure_bind_zone.sh " .
                    escapeshellarg($domain) . ' ' . escapeshellarg($serverIP);

        exec($bindCmd . ' 2>&1', $bindOutput, $bindStatus);

        if ($bindStatus !== 0) {
            \Log::error("Fallo configurando BIND", [
                'command' => $bindCmd,
                'output' => $bindOutput
            ]);
            return response()->json([
                'success' => false,
                'step' => 'bind',
                'message' => "Error al configurar DNS: " . implode("\n", $bindOutput)
            ], 500);
        }

        \Log::info("BIND configurado correctamente", ['output' => $bindOutput]);

        return response()->json([
            'success' => true,
            'message' => "Infraestructura configurada correctamente para {$domain}",
            'domain' => $domain
        ]);

    } catch (\Exception $e) {
        \Log::error('Error general en configuración de infraestructura', [
            'domain' => $domain,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error inesperado: ' . $e->getMessage()
        ], 500);
    }
        //FIN PRUEBAS---------------------------------------------------------------------

            \Log::info('=== PUBLICACIÓN COMPLETADA EXITOSAMENTE ===');
    
            // Determinar la URL inicial según si hay página de bienvenida
            $initialUrl = url('webs/' . $domain . '/main.html'); // Por defecto main.html
    
            // Si hay página de bienvenida, empezar por welcome.html
            if (!empty($data['welcomeData'])) {
                $initialUrl = url('webs/' . $domain . '/welcome.html');
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Web publicada con éxito',
                'url' => $initialUrl
            ]);

        } catch (\Exception $e) {
            \Log::error('=== ERROR EN PUBLISH ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al publicar la web: ' . $e->getMessage()
            ], 500);
        }


    }

    // Método para republicar una web existente (nuevo)
    public function republish(Web $web)
    {
        try {
            // Verificar permisos
            if (!auth()->user()->webs->contains($web->id) && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para republicar esta web.'
                ], 403);
            }

            $webPath = public_path('webs/' . $web->url);
            
            // Crear directorio para la web si no existe
            if (!file_exists($webPath)) {
                mkdir($webPath, 0755, true);
                mkdir($webPath . '/images', 0755, true);
                mkdir($webPath . '/css', 0755, true);
            }

            // Preparar datos para regenerar archivos
            $data = [
                'mainPageData' => json_encode($web->main_page_data),
                'welcomeData' => $web->welcome_page_data ? json_encode($web->welcome_page_data) : null,
                'contactData' => $web->contact_page_data ? json_encode($web->contact_page_data) : null,
                'images' => []
            ];

            // Recrear archivos
            $this->processMainPage($web->id, $data, $webPath);
            
            if ($web->welcome_page_data) {
                $this->processWelcomePage($web->id, $data, $webPath);
            }
            
            if ($web->contact_page_data) {
                $this->processContactPage($web->id, $data, $webPath);
            }

            // Actualizar estado de publicación
            $web->update([
                'is_published' => true,
                'published_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Web republicada con éxito',
                'url' => $web->getPublicUrl()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al republicar la web: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al republicar la web'
            ], 500);
        }
    }

    // Resto de métodos existentes (mantener todos los métodos de generación HTML/CSS)
    
    private function generateMainHtml($data, $images)
    {
        $logoUrl = isset($images['main-logo']) ? $images['main-logo'] : '';
        $mainPhotoUrl = isset($images['main-photo']) ? $images['main-photo'] : '';
        $headerText = isset($data['header']['text']) ? $data['header']['text'] : 'Mi Página';
        $logoPosition = isset($data['logo']['position']) ? $data['logo']['position'] : 'center';
        $footerText = isset($data['footer']['text']) ? $data['footer']['text'] : '';
        
        $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $headerText . '</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <header>
        <div class="header-container">
            ' . $this->generateLogoHtml($logoUrl, $logoPosition) . '
            <h1>' . $headerText . '</h1>
        </div>
    </header>
    
    ' . $this->generateNavbarHtml() . '
    
    <main>
        ' . $this->generateMainContentHtml($data, $mainPhotoUrl) . '
    </main>
    
    <footer>
        <p>' . $footerText . '</p>
    </footer>
</body>
</html>';

        return $html;
    }

    private function generateMainContentHtml($data, $mainPhotoUrl)
    {
        $photoTitle = isset($data['photoContent']['title']) ? htmlspecialchars($data['photoContent']['title']) : '';
        $photoDesc = isset($data['photoContent']['description']) ? htmlspecialchars($data['photoContent']['description']) : '';
        
        $content = '';
        
        if ($mainPhotoUrl) {
            $content .= '<div class="main-photo-container">
    <img src="' . $mainPhotoUrl . '" alt="Foto principal">
    <h2>' . $photoTitle . '</h2>
</div>';
        }
        
        if ($photoDesc) {
            $align = isset($data['photoContent']['align']) ? $data['photoContent']['align'] : 'justify';
            $content .= '<p class="photo-description" style="text-align: ' . $align . '">' . $photoDesc . '</p>';
        }
        
        // Contenido adicional (video, mapa, etc.)
        if (isset($data['contentType']) && $data['contentType'] !== 'none') {
            $content .= $this->generateAdditionalContentHtml($data);
        }
        
        return $content;
    }

    private function generateAdditionalContentHtml($data)
    {
        switch ($data['contentType']) {
            case 'feature-module':
                return $this->generateFeatureModuleHtml($data['featureModule']);
            case 'video':
                return $this->generateVideoHtml($data['video']);
            case 'map':
                return $this->generateMapHtml($data['map']);
            default:
                return '';
        }
    }

    private function generateFeatureModuleHtml($data)
    {
        $columns = '';
        foreach ($data['columns'] as $column) {
            $icon = isset($column['icon']) ? htmlspecialchars($column['icon']) : 'star';
            $text = isset($column['text']) ? htmlspecialchars($column['text']) : '';
            $columns .= '<div class="feature-column">
    <div class="feature-icon">' . $this->getIconSvg($icon) . '</div>
    <p>' . $text . '</p>
</div>';
        }
        
        return '<div class="feature-module">
    ' . $columns . '
</div>';
    }

    private function generateVideoHtml($data)
    {
        $videoId = $this->extractVideoId(isset($data['url']) ? $data['url'] : '');
        $description = isset($data['description']) ? htmlspecialchars($data['description']) : '';
        
        if (!$videoId) return '';
        
        return '<div class="video-container">
    <iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>
    <p>' . $description . '</p>
</div>';
    }

    private function generateMapHtml($data)
    {
        $address = isset($data['address']) ? urlencode($data['address']) : '';
        $description = isset($data['description']) ? htmlspecialchars($data['description']) : '';
        
        if (!$address) return '';
        
        return '<div class="map-container">
    <iframe src="https://maps.google.com/maps?q=' . $address . '&output=embed"></iframe>
    <p>' . $description . '</p>
</div>';
    }

    private function generateLogoHtml($logoUrl, $position)
    {
        if (!$logoUrl) return '';
        
        return '<div class="logo" style="float: ' . $position . '">
    <img src="' . $logoUrl . '" alt="Logo">
</div>';
    }

    private function generateNavbarHtml()
    {
        $contactPage = request()->input('contactData') ? true : false;
        
        if (!$contactPage) return '';
        
        return '<nav>
    <a href="main.html">Inicio</a>
    <a href="contact.html">Contacto</a>
</nav>';
    }

    private function generateMainCss($data)
    {
        $bgColor = isset($data['bgColor']) ? $data['bgColor'] : '#ffffff';
        $textColor = isset($data['textColor']) ? $data['textColor'] : '#000000';
        $headerBgColor = isset($data['header']['bgColor']) ? $data['header']['bgColor'] : '#f8f8f8';
        $headerTextColor = isset($data['header']['textColor']) ? $data['header']['textColor'] : '#000000';
        $footerBgColor = isset($data['footer']['bgColor']) ? $data['footer']['bgColor'] : '#f8f8f8';
        $footerTextColor = isset($data['footer']['textColor']) ? $data['footer']['textColor'] : '#000000';
        $fontFamily = isset($data['fontFamily']) ? $data['fontFamily'] : 'Arial, sans-serif';
        
        return 'body {
    font-family: ' . $fontFamily . ';
    background-color: ' . $bgColor . ';
    color: ' . $textColor . ';
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

header {
    background-color: ' . $headerBgColor . ';
    padding: 15px;
    color: ' . $headerTextColor . ';
}

.header-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
}

.logo img {
    max-height: 60px;
    width: auto;
}

nav {
    background-color: ' . $this->lightenColor($headerBgColor, 20) . ';
    padding: 10px 0;
    text-align: center;
}

nav a {
    color: ' . $textColor . ';
    text-decoration: none;
    padding: 8px 16px;
    margin: 0 5px;
}

nav a:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

main {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.main-photo-container {
    position: relative;
    margin-bottom: 20px;
}

.main-photo-container img {
    width: 100%;
    aspect-ratio: 4 / 1;
    object-fit: cover;
}

.main-photo-container h2 {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    background-color: rgba(0, 0, 0, 0.7);
    padding: 10px 20px;
    border-radius: 5px;
}

.photo-description {
    margin: 15px 0;
    padding: 0 30px;
}

.feature-module {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    margin: 20px 0;
    background-color: #f8fafc;
    border-radius: 8px;
}

.feature-column {
    flex: 1;
    padding: 0 15px;
    text-align: center;
}

.feature-icon svg {
    width: 48px;
    height: 48px;
}

.video-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    margin: 20px 0;
    overflow: hidden;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.map-container {
    height: 300px;
    margin: 20px 0;
    border-radius: 8px;
    overflow: hidden;
}

.map-container iframe {
    width: 100%;
    height: 100%;
}

footer {
    background-color: ' . $footerBgColor . ';
    padding: 15px;
    text-align: center;
    color: ' . $footerTextColor . ';
    margin-top: auto;
}';
    }

    private function generateWelcomeHtml($data, $images)
    {
        $logoUrl = isset($images['logoBienvenida']) ? $images['logoBienvenida'] : '';
        $bgImageUrl = isset($images['background']) ? $images['background'] : '';
        $bgColor = isset($data['bgColor']) ? $data['bgColor'] : '#ffffff';
        $title = isset($data['title']) ? htmlspecialchars($data['title']) : '';
        $message = isset($data['message']) ? htmlspecialchars($data['message']) : '';
        $buttonText = isset($data['buttonText']) ? htmlspecialchars($data['buttonText']) : 'Entrar a la web';
        $buttonColor = isset($data['buttonColor']) ? $data['buttonColor'] : '#0000ff';
        $buttonTextColor = isset($data['buttonTextColor']) ? $data['buttonTextColor'] : '#ffffff';
        $logoSize = isset($data['logoSize']) ? $data['logoSize'] : '100px';
        $logoPosition = isset($data['logoPosition']) ? $data['logoPosition'] : 'center';
        
        $bgStyle = $bgImageUrl ? 
            "background-image: url('" . $bgImageUrl . "'); background-size: cover;" : 
            "background-color: " . $bgColor . ";";
        
        $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
    <link rel="stylesheet" href="css/welcome.css">
</head>
<body style="' . $bgStyle . '">
    <div class="welcome-container">
        ' . $this->generateWelcomeLogoHtml($logoUrl, $logoSize, $logoPosition) . '
        <div class="welcome-content">
            <h1>' . $title . '</h1>
            <p>' . $message . '</p>
            <a href="main.html" class="enter-button" style="background-color: ' . $buttonColor . '; color: ' . $buttonTextColor . ';">
                ' . $buttonText . '
            </a>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    private function generateWelcomeLogoHtml($logoUrl, $size, $position)
    {
        if (!$logoUrl) return '';
        
        $style = "width: " . $size . ";";
        $containerStyle = '';
        
        if ($position === 'left') {
            $containerStyle = 'flex-direction: row; justify-content: flex-start; text-align: left;';
        } elseif ($position === 'right') {
            $containerStyle = 'flex-direction: row-reverse; justify-content: flex-end; text-align: right;';
        } else {
            $containerStyle = 'flex-direction: column; align-items: center; text-align: center;';
        }
        
        return '<div class="logo-container" style="' . $containerStyle . '">
    <img src="' . $logoUrl . '" alt="Logo" style="' . $style . '">
</div>';
    }

    private function generateWelcomeCss($data)
    {
        $contentBgColor = isset($data['contentBgColor']) ? $data['contentBgColor'] : '#ffffff';
        $contentBgOpacity = isset($data['contentBgOpacity']) ? $data['contentBgOpacity'] : 0.8;
        $contentTextColor = isset($data['contentTextColor']) ? $data['contentTextColor'] : '#000000';
        $titleFontSize = isset($data['titleFontSize']) ? $data['titleFontSize'] : '24px';
        $paragraphFontSize = isset($data['paragraphFontSize']) ? $data['paragraphFontSize'] : '16px';
        $titleBold = isset($data['titleBold']) && $data['titleBold'] ? 'bold' : 'normal';
        $titleItalic = isset($data['titleItalic']) && $data['titleItalic'] ? 'italic' : 'normal';
        $paragraphBold = isset($data['paragraphBold']) && $data['paragraphBold'] ? 'bold' : 'normal';
        $paragraphItalic = isset($data['paragraphItalic']) && $data['paragraphItalic'] ? 'italic' : 'normal';
        $fontFamily = isset($data['fontFamily']) ? $data['fontFamily'] : 'Arial, sans-serif';
        
        $r = $this->hexToRgb($contentBgColor, 'r');
        $g = $this->hexToRgb($contentBgColor, 'g');
        $b = $this->hexToRgb($contentBgColor, 'b');
        
        return 'body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: ' . $fontFamily . ';
}

.welcome-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px;
    max-width: 800px;
    margin: 0 auto;
}

.logo-container {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 30px;
}

.welcome-content {
    background-color: rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $contentBgOpacity . ');
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    color: ' . $contentTextColor . ';
}

.welcome-content h1 {
    font-size: ' . $titleFontSize . ';
    font-weight: ' . $titleBold . ';
    font-style: ' . $titleItalic . ';
    margin-top: 0;
}

.welcome-content p {
    font-size: ' . $paragraphFontSize . ';
    font-weight: ' . $paragraphBold . ';
    font-style: ' . $paragraphItalic . ';
    margin-bottom: 30px;
}

.enter-button {
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

.enter-button:hover {
    opacity: 0.9;
}';
    }

    private function generateContactHtml($data)
{
    $showMap = isset($data['showMap']) ? $data['showMap'] : false;
    $mapAddress = isset($data['mapAddress']) ? htmlspecialchars($data['mapAddress']) : '';
    $contactInfo = isset($data['contactInfo']) ? $data['contactInfo'] : [];
    
    // Generar items de contacto con estilo mejorado
    $contactItems = '';
    $hasContactInfo = false;
    
    foreach ($contactInfo as $type => $info) {
        if (isset($info['selected']) && $info['selected'] && !empty($info['text'])) {
            $hasContactInfo = true;
            $title = ucfirst($type);
            $text = htmlspecialchars($info['text']);
            $contactItems .= '<div class="contact-item">
    <div class="contact-title">' . $title . '</div>
    <div class="contact-text">' . $text . '</div>
</div>';
        }
    }
    
    // Si no hay información de contacto, mostrar placeholder
    if (!$hasContactInfo) {
        $contactItems = '<div class="contact-placeholder">
    <div class="contact-placeholder-icon">📞</div>
    <div class="contact-placeholder-text">Información de contacto no disponible</div>
</div>';
    }
    
    // Sección del mapa
    $mapSection = '';
    if ($showMap && $mapAddress) {
        $mapSection = '<div class="map-section">
    <h2 class="map-title">Nuestra Ubicación</h2>
    <p class="map-address">' . htmlspecialchars($mapAddress) . '</p>
    <div class="map-container">
        <iframe src="https://maps.google.com/maps?q=' . urlencode($mapAddress) . '&output=embed" 
                frameborder="0" allowfullscreen></iframe>
    </div>
</div>';
    }
    
    $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Contacto</h1>
        </div>
    </header>
    
    <nav>
        <a href="main.html">Inicio</a>
        <a href="contact.html">Contacto</a>
    </nav>
    
    <main class="contact-main">
        <div class="contact-content">
            <div class="contact-info-section">
                <h2 class="section-title">Información de Contacto</h2>
                <div class="contact-items-container">
                    ' . $contactItems . '
                </div>
            </div>
            
            ' . $mapSection . '
            
            <div class="contact-form-section">
                <h2 class="section-title">Envíanos un mensaje</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" id="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="form-label">Asunto:</label>
                        <input type="text" id="subject" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Mensaje:</label>
                        <textarea id="message" class="form-textarea" required></textarea>
                    </div>
                    <button type="submit" class="form-submit">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </main>
    
    <footer>
        <p>© 2023 Todos los derechos reservados</p>
    </footer>
</body>
</html>';

    return $html;
}
private function generateContactCss($mainData)
{
    $bgColor = isset($mainData['bgColor']) ? $mainData['bgColor'] : '#ffffff';
    $textColor = isset($mainData['textColor']) ? $mainData['textColor'] : '#000000';
    $headerBgColor = isset($mainData['header']['bgColor']) ? $mainData['header']['bgColor'] : '#f8f8f8';
    $headerTextColor = isset($mainData['header']['textColor']) ? $mainData['header']['textColor'] : '#000000';
    $footerBgColor = isset($mainData['footer']['bgColor']) ? $mainData['footer']['bgColor'] : '#f8f8f8';
    $footerTextColor = isset($mainData['footer']['textColor']) ? $mainData['footer']['textColor'] : '#000000';
    $fontFamily = isset($mainData['fontFamily']) ? $mainData['fontFamily'] : 'Arial, sans-serif';
    
    return 'body {
    font-family: ' . $fontFamily . ';
    background-color: ' . $bgColor . ';
    color: ' . $textColor . ';
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

header {
    background-color: ' . $headerBgColor . ';
    padding: 15px;
    color: ' . $headerTextColor . ';
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}

nav {
    background-color: ' . $this->lightenColor($headerBgColor, 20) . ';
    padding: 10px 0;
    text-align: center;
}

nav a {
    color: ' . $textColor . ';
    text-decoration: none;
    padding: 8px 16px;
    margin: 0 5px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

nav a:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.contact-main {
    padding: 40px 20px;
    min-height: calc(100vh - 200px);
}

.contact-content {
    max-width: 1000px;
    margin: 0 auto;
}

.section-title {
    color: ' . $textColor . ';
    font-size: 28px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 3px solid #3b82f6;
    padding-bottom: 10px;
}

/* Sección de información de contacto */
.contact-info-section {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 40px;
    border: 1px solid #e9ecef;
}

.contact-items-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.contact-item {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.contact-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.contact-title {
    font-weight: bold;
    color: ' . $textColor . ';
    font-size: 16px;
    margin-bottom: 8px;
    border-bottom: 2px solid #3b82f6;
    padding-bottom: 5px;
}

.contact-text {
    color: #666;
    font-size: 15px;
    line-height: 1.5;
}

.contact-placeholder {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #ddd;
}

.contact-placeholder-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.contact-placeholder-text {
    font-size: 18px;
    font-style: italic;
}

/* Sección del mapa */
.map-section {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 40px;
    border: 1px solid #e9ecef;
}

.map-title {
    color: ' . $textColor . ';
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 15px;
}

.map-address {
    color: #666;
    text-align: center;
    font-size: 16px;
    margin-bottom: 20px;
    font-style: italic;
}

.map-container {
    width: 100%;
    height: 350px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: 2px solid #e9ecef;
}

.map-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Sección del formulario de contacto */
.contact-form-section {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.contact-form {
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: ' . $textColor . ';
    font-weight: 600;
    font-size: 14px;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 2px solid #e9ecef;
    font-size: 16px;
    transition: border-color 0.3s ease;
    background-color: #fff;
    box-sizing: border-box;
    font-family: ' . $fontFamily . ';
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.form-submit {
    width: 100%;
    background-color: #3b82f6;
    color: white;
    border: none;
    padding: 15px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-submit:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

footer {
    background-color: ' . $footerBgColor . ';
    padding: 15px;
    text-align: center;
    color: ' . $footerTextColor . ';
    margin-top: auto;
}

/* Responsive design */
@media (max-width: 768px) {
    .contact-items-container {
        grid-template-columns: 1fr;
    }
    
    .contact-main {
        padding: 20px 10px;
    }
    
    .contact-info-section,
    .map-section,
    .contact-form-section {
        padding: 20px;
    }
    
    .section-title {
        font-size: 24px;
    }
    
    .map-container {
        height: 250px;
    }
}';
}
    // Funciones auxiliares
    private function extractVideoId($url)
    {
        // YouTube
        $youtubeRegExp = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/';
        $youtubeMatch = preg_match($youtubeRegExp, $url, $matches);
        if ($youtubeMatch && strlen($matches[2]) === 11) {
            return $matches[2];
        }
        
        // Vimeo
        $vimeoRegExp = '/(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/';
        $vimeoMatch = preg_match($vimeoRegExp, $url, $matches);
        if ($vimeoMatch && isset($matches[5])) {
            return $matches[5];
        }
        
        return null;
    }

    private function getIconSvg($iconName)
    {
        $icons = [
            'star' => '<path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>',
            'shield' => '<path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3z"/>',
            'trophy' => '<path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/>',
            'lightbulb' => '<path d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7z"/>',
            'heart' => '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>'
        ];
        
        $path = isset($icons[$iconName]) ? $icons[$iconName] : $icons['star'];
        
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b82f6" width="48px" height="48px">
    ' . $path . '
</svg>';
    }

    private function lightenColor($hex, $percent)
    {
        $rgb = $this->hexToRgb($hex);
        $r = min(255, $rgb['r'] + round(2.55 * $percent));
        $g = min(255, $rgb['g'] + round(2.55 * $percent));
        $b = min(255, $rgb['b'] + round(2.55 * $percent));
        
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    private function hexToRgb($hex, $component = null)
    {
        $hex = str_replace('#', '', $hex);
        $length = strlen($hex);
        
        $r = hexdec($length == 3 ? $hex[0].$hex[0] : substr($hex, 0, 2));
        $g = hexdec($length == 3 ? $hex[1].$hex[1] : substr($hex, 2, 2));
        $b = hexdec($length == 3 ? $hex[2].$hex[2] : substr($hex, 4, 2));
        
        if ($component) {
            return $component;
        }
        
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    private function extractDomainName($domain)
    {
        // Remover www. si existe
        $domain = preg_replace('/^www\./', '', $domain);
        
        // Encontrar la última posición del punto
        $lastDotPosition = strrpos($domain, '.');
        
        if ($lastDotPosition !== false) {
            // Extraer todo antes del último punto
            return substr($domain, 0, $lastDotPosition);
        }
        
        // Si no hay punto, devolver el dominio completo
        return $domain;
    }

    private function saveImage($base64Data, $webPath, $imageId)
{
    try {
        // Validar que los datos no estén vacíos
        if (empty($base64Data)) {
            throw new \Exception('Datos de imagen vacíos para: ' . $imageId);
        }

        // Remover el prefijo data:image/xxx;base64, si existe
        if (strpos($base64Data, 'data:image') === 0) {
            $base64Data = preg_replace('/^data:image\/[^;]+;base64,/', '', $base64Data);
        }

        // Decodificar base64
        $imageData = base64_decode($base64Data);
        if ($imageData === false) {
            throw new \Exception('Error decodificando base64 para imagen: ' . $imageId);
        }

        // Validar que la decodificación produjo datos
        if (empty($imageData)) {
            throw new \Exception('Imagen decodificada está vacía: ' . $imageId);
        }

        // Determinar extensión (por defecto png)
        $extension = 'png';
        
        // Intentar detectar el tipo de imagen desde los datos decodificados
        $imageInfo = @getimagesizefromstring($imageData);
        if ($imageInfo !== false) {
            switch ($imageInfo['mime']) {
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    break;
                case 'image/webp':
                    $extension = 'webp';
                    break;
            }
        }

        // Crear nombre de archivo seguro
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $imageId) . '.' . $extension;
        $imagePath = 'images/' . $filename;
        $fullPath = $webPath . '/' . $imagePath;

        // Verificar que el directorio de imágenes existe
        $imageDir = dirname($fullPath);
        if (!is_dir($imageDir)) {
            if (!mkdir($imageDir, 0755, true)) {
                throw new \Exception('No se pudo crear el directorio de imágenes: ' . $imageDir);
            }
        }

        // Guardar la imagen
        $bytesWritten = file_put_contents($fullPath, $imageData);
        if ($bytesWritten === false) {
            throw new \Exception('Error escribiendo archivo de imagen: ' . $fullPath);
        }

        \Log::info('Imagen guardada exitosamente', [
            'imageId' => $imageId,
            'filename' => $filename,
            'path' => $imagePath,
            'size' => $bytesWritten . ' bytes'
        ]);

        return $imagePath;

    } catch (\Exception $e) {
        \Log::error('Error en saveImage: ' . $e->getMessage(), [
            'imageId' => $imageId,
            'webPath' => $webPath,
            'base64_length' => strlen($base64Data)
        ]);
        throw $e;
    }
}

    private function processMainPage($webId, $data, $webPath)
{
    \Log::info('=== PROCESANDO PÁGINA PRINCIPAL ===', [
        'webId' => $webId,
        'has_mainPageData' => isset($data['mainPageData']),
        'has_images' => isset($data['images']),
        'images_count' => isset($data['images']) && is_array($data['images']) ? count($data['images']) : 0
    ]);

    $mainData = json_decode($data['mainPageData'], true);
    if (!$mainData) {
        throw new \Exception('Error decodificando datos de página principal');
    }
    
    // Procesar imágenes
    $images = [];
    if (isset($data['images']) && is_array($data['images']) && count($data['images']) > 0) {
        \Log::info('Procesando imágenes para página principal...');
        foreach ($data['images'] as $imageId => $imageData) {
            try {
                if (strpos($imageId, 'main-') === 0) {
                    \Log::info('Guardando imagen: ' . $imageId);
                    $imagePath = $this->saveImage($imageData, $webPath, $imageId);
                    $images[$imageId] = $imagePath;
                    \Log::info('Imagen guardada: ' . $imageId . ' -> ' . $imagePath);
                }
            } catch (\Exception $e) {
                \Log::warning('Error guardando imagen ' . $imageId . ': ' . $e->getMessage());
                // Continuar con las demás imágenes
            }
        }
    } else {
        \Log::info('No hay imágenes para procesar en página principal');
    }

    // Generar HTML
    \Log::info('Generando HTML principal...');
    $html = $this->generateMainHtml($mainData, $images);
    $htmlPath = $webPath . '/main.html';
    if (file_put_contents($htmlPath, $html) === false) {
        throw new \Exception('No se pudo crear el archivo main.html');
    }
    \Log::info('HTML principal creado: ' . $htmlPath);

    // Generar CSS
    \Log::info('Generando CSS principal...');
    $css = $this->generateMainCss($mainData);
    $cssPath = $webPath . '/css/main.css';
    if (file_put_contents($cssPath, $css) === false) {
        throw new \Exception('No se pudo crear el archivo main.css');
    }
    \Log::info('CSS principal creado: ' . $cssPath);

    // Guardar en BD
    \Log::info('Guardando configuración en BD...');
    Page::updateOrCreate(
        ['web_id' => $webId, 'type' => 'main'],
        ['settings' => json_encode($mainData)]
    );
    \Log::info('Página principal guardada en BD');
}

private function processWelcomePage($webId, $data, $webPath)
{
    \Log::info('=== PROCESANDO PÁGINA DE BIENVENIDA ===', [
        'webId' => $webId,
        'has_welcomeData' => isset($data['welcomeData']),
        'has_images' => isset($data['images']),
        'images_count' => isset($data['images']) && is_array($data['images']) ? count($data['images']) : 0
    ]);

    $welcomeData = json_decode($data['welcomeData'], true);
    if (!$welcomeData) {
        throw new \Exception('Error decodificando datos de página de bienvenida');
    }
    
    // Procesar imágenes
    $images = [];
    if (isset($data['images']) && is_array($data['images']) && count($data['images']) > 0) {
        \Log::info('Procesando imágenes para página de bienvenida...');
        foreach ($data['images'] as $imageId => $imageData) {
            try {
                if (strpos($imageId, 'welcome-') === 0 || $imageId === 'logoBienvenida' || $imageId === 'background') {
                    \Log::info('Guardando imagen: ' . $imageId);
                    $imagePath = $this->saveImage($imageData, $webPath, $imageId);
                    $images[$imageId] = $imagePath;
                    \Log::info('Imagen guardada: ' . $imageId . ' -> ' . $imagePath);
                }
            } catch (\Exception $e) {
                \Log::warning('Error guardando imagen ' . $imageId . ': ' . $e->getMessage());
                // Continuar con las demás imágenes
            }
        }
    } else {
        \Log::info('No hay imágenes para procesar en página de bienvenida');
    }

    // Generar HTML
    \Log::info('Generando HTML de bienvenida...');
    $html = $this->generateWelcomeHtml($welcomeData, $images);
    $htmlPath = $webPath . '/welcome.html';
    if (file_put_contents($htmlPath, $html) === false) {
        throw new \Exception('No se pudo crear el archivo welcome.html');
    }
    \Log::info('HTML de bienvenida creado: ' . $htmlPath);

    // Generar CSS
    \Log::info('Generando CSS de bienvenida...');
    $css = $this->generateWelcomeCss($welcomeData);
    $cssPath = $webPath . '/css/welcome.css';
    if (file_put_contents($cssPath, $css) === false) {
        throw new \Exception('No se pudo crear el archivo welcome.css');
    }
    \Log::info('CSS de bienvenida creado: ' . $cssPath);

    // Guardar en BD
    \Log::info('Guardando configuración en BD...');
    Page::updateOrCreate(
        ['web_id' => $webId, 'type' => 'welcome'],
        ['settings' => json_encode($welcomeData)]
    );
    \Log::info('Página de bienvenida guardada en BD');
}

private function processContactPage($webId, $data, $webPath)
{
    \Log::info('=== PROCESANDO PÁGINA DE CONTACTO ===', [
        'webId' => $webId,
        'has_contactData' => isset($data['contactData'])
    ]);

    $contactData = json_decode($data['contactData'], true);
    if (!$contactData) {
        throw new \Exception('Error decodificando datos de página de contacto');
    }
    
    // Obtener datos de la página principal para mantener consistencia de diseño
    $mainData = json_decode($data['mainPageData'], true);
    if (!$mainData) {
        $mainData = []; // Valores por defecto si no hay datos principales
    }
    
    // Generar HTML
    \Log::info('Generando HTML de contacto...');
    $html = $this->generateContactHtml($contactData);
    $htmlPath = $webPath . '/contact.html';
    if (file_put_contents($htmlPath, $html) === false) {
        throw new \Exception('No se pudo crear el archivo contact.html');
    }
    \Log::info('HTML de contacto creado: ' . $htmlPath);

    // Generar CSS específico para contacto
    \Log::info('Generando CSS de contacto...');
    $css = $this->generateContactCss($mainData);
    $cssPath = $webPath . '/css/contact.css';
    if (file_put_contents($cssPath, $css) === false) {
        throw new \Exception('No se pudo crear el archivo contact.css');
    }
    \Log::info('CSS de contacto creado: ' . $cssPath);

    // Guardar en BD
    \Log::info('Guardando configuración en BD...');
    Page::updateOrCreate(
        ['web_id' => $webId, 'type' => 'contact'],
        ['settings' => json_encode($contactData)]
    );
    \Log::info('Página de contacto guardada en BD');
}
    public function saveDraft(Request $request)
{
    try {
        \Log::info('=== INICIANDO GUARDADO DE BORRADOR ===', [
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
            'has_mainPageData' => $request->has('mainPageData'),
            'has_welcomeData' => $request->has('welcomeData'),
            'has_contactData' => $request->has('contactData'),
            'has_images' => $request->has('images')
        ]);

        // 1. Verificar autenticación
        $user = Auth::user();
        if (!$user) {
            \Log::warning('Usuario no autenticado intentando guardar borrador');
            return response()->json([
                'success' => false,
                'message' => 'Authentication error. Please log in again.'
            ], 401);
        }

        // 2. Obtener y validar datos básicos
        $user_id = $request->input('user_id');
        $name = $request->input('name');
        
        // Verificar que el user_id coincida con el usuario autenticado
        if ($user->id != $user_id) {
            \Log::warning('User ID mismatch', [
                'auth_user_id' => $user->id,
                'request_user_id' => $user_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autorizado.'
            ], 403);
        }
        
        // Validar nombre
        if (!$name || trim($name) === '') {
            return response()->json([
                'success' => false,
                'message' => 'Se requiere un nombre para la web.'
            ], 400);
        }
        
        // Validar datos mínimos requeridos
        if (!$request->has('mainPageData') || empty($request->input('mainPageData'))) {
            return response()->json([
                'success' => false,
                'message' => 'Se requieren datos de la página principal.'
            ], 400);
        }

        // 3. Procesar datos de páginas
        $mainPageData = null;
        $welcomePageData = null;
        $contactPageData = null;
        
        // Procesar mainPageData (obligatorio)
        $mainPageDataRaw = $request->input('mainPageData');
        $mainPageData = json_decode($mainPageDataRaw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Error decodificando mainPageData: ' . json_last_error_msg());
            return response()->json([
                'success' => false,
                'message' => 'Error en los datos de la página principal.'
            ], 400);
        }
        
        // Procesar welcomeData (opcional)
        if ($request->has('welcomeData') && !empty($request->input('welcomeData'))) {
            $welcomeDataRaw = $request->input('welcomeData');
            $welcomePageData = json_decode($welcomeDataRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::warning('Error decodificando welcomeData: ' . json_last_error_msg());
                $welcomePageData = null;
            }
        }
        
        // Procesar contactData (opcional)
        if ($request->has('contactData') && !empty($request->input('contactData'))) {
            $contactDataRaw = $request->input('contactData');
            $contactPageData = json_decode($contactDataRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::warning('Error decodificando contactData: ' . json_last_error_msg());
                $contactPageData = null;
            }
        }

        // 4. Preparar configuración de diseño
        $designConfig = [
            'welcomeMessage' => !empty($welcomePageData),
            'contactPage' => !empty($contactPageData)
        ];
        
        \Log::info('Datos procesados correctamente', [
            'name' => trim($name),
            'design_config' => $designConfig,
            'has_main_data' => !empty($mainPageData),
            'has_welcome_data' => !empty($welcomePageData),
            'has_contact_data' => !empty($contactPageData)
        ]);

        // 5. Crear registro en la tabla webs como borrador
        $web = Web::create([
            'url' => '', // Sin URL ya que es borrador
            'user_id' => $user_id,
            'name' => trim($name),
            'design_config' => $designConfig,
            'welcome_page_data' => $welcomePageData,
            'main_page_data' => $mainPageData,
            'contact_page_data' => $contactPageData,
            'is_published' => false,
            'published_at' => null
        ]);
        
        \Log::info('Web creada con ID: ' . $web->id);

        // 6. Crear registro en la tabla intermedia user_web
        DB::table('user_web')->insert([
            'user_id' => $user_id,
            'web_id' => $web->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 7. Procesar imágenes si existen
        if ($request->has('images') && !empty($request->input('images'))) {
            try {
                $imagesJson = $request->input('images');
                $images = json_decode($imagesJson, true);
                
                if ($images && is_array($images) && count($images) > 0) {
                    // Crear directorio para las imágenes del borrador
                    $draftPath = storage_path('app/drafts/' . $web->id);
                    if (!file_exists($draftPath)) {
                        mkdir($draftPath, 0755, true);
                    }
                    
                    // Guardar cada imagen
                    $savedImages = [];
                    foreach ($images as $imageId => $imageData) {
                        try {
                            $imageContent = base64_decode($imageData);
                            if ($imageContent !== false) {
                                $extension = 'png'; // Podrías detectar la extensión real del base64
                                $filename = $imageId . '.' . $extension;
                                $filepath = $draftPath . '/' . $filename;
                                
                                if (file_put_contents($filepath, $imageContent)) {
                                    $savedImages[] = $imageId;
                                }
                            }
                        } catch (\Exception $imageError) {
                            \Log::warning("Error guardando imagen {$imageId}: " . $imageError->getMessage());
                        }
                    }
                    
                    \Log::info('Imágenes guardadas: ' . implode(', ', $savedImages));
                }
            } catch (\Exception $e) {
                \Log::warning('Error procesando imágenes del borrador: ' . $e->getMessage());
                // No fallar el proceso por error en imágenes
            }
        }

        // 8. Respuesta exitosa
        \Log::info('=== BORRADOR GUARDADO EXITOSAMENTE ===', [
            'web_id' => $web->id,
            'user_id' => $user_id,
            'name' => $web->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Web guardada como borrador exitosamente',
            'web_id' => $web->id,
            'web_name' => $web->name,
            'debug_info' => [
                'design_config' => $designConfig,
                'has_welcome_page' => $designConfig['welcomeMessage'],
                'has_contact_page' => $designConfig['contactPage'],
                'created_at' => $web->created_at->toISOString()
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('=== ERROR AL GUARDAR BORRADOR ===', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor al guardar el borrador'
        ], 500);
    }
}
}