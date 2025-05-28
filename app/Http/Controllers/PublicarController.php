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
        // Crear directorio para la web
        if (!file_exists($webPath)) {
            mkdir($webPath, 0755, true);
            mkdir($webPath . '/images', 0755, true);
            mkdir($webPath . '/css', 0755, true);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            Log::warning('User is null despite auth middleware');
            return back()->with('error', 'Authentication error. Please log in again.');
        }
        
        // Crear registro en la tabla webs
        $web = Web::create([
            'url' => $domain,
            'user_id' => $user_id,
            'name' => $name

        ]);
        
        // Obtener el ID de la web recién creada
        $webId = $web->id;

        // Crear registro en la tabla intermedia user_web
        DB::table('user_web')->insert([
            'user_id' => $user_id,
            'web_id' => $webId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

            // Procesar cada tipo de página
            $this->processMainPage($webId, $data, $webPath);
            
            if (isset($data['welcomeData'])) {
                $this->processWelcomePage($webId, $data, $webPath);
            }
            
            if (isset($data['contactData'])) {
                $this->processContactPage($webId, $data, $webPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Web publicada con éxito',
                'url' => url('webs/' . $domain . '/main.html')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al publicar la web: ' . $e->getMessage()
            ], 500);
        }
    }
   private function saveImage($base64Data, $webPath, $imageId)
    {
        $imageData = base64_decode($base64Data);
        $extension = 'png'; // o detectar desde el base64
        $filename = $imageId . '.' . $extension;
        $imagePath = 'images/' . $filename;
        
        file_put_contents($webPath . '/' . $imagePath, $imageData);
        
        return $imagePath;
    }
    private function processMainPage($webId, $data, $webPath)
    {
        $mainData = json_decode($data['mainPageData'], true);
        
        // Guardar imágenes
        $images = [];
        if (isset($data['images'])) {
            foreach ($data['images'] as $imageId => $imageData) {
                if (strpos($imageId, 'main-') === 0) {
                    $imagePath = $this->saveImage($imageData, $webPath, $imageId);
                    $images[$imageId] = $imagePath;
                }
            }
        }

        // Generar HTML
        $html = $this->generateMainHtml($mainData, $images);
        file_put_contents($webPath . '/main.html', $html);

        // Generar CSS
        $css = $this->generateMainCss($mainData);
        file_put_contents($webPath . '/css/main.css', $css);

        // Guardar en BD
        Page::create([
            'web_id' => $webId,
            'type' => 'main',
            'settings' => json_encode($mainData)
        ]);
    }

    private function processWelcomePage($webId, $data, $webPath)
    {
        $welcomeData = json_decode($data['welcomeData'], true);
        
        // Guardar imágenes
        $images = [];
        if (isset($data['images'])) {
            foreach ($data['images'] as $imageId => $imageData) {
                if (strpos($imageId, 'welcome-') === 0 || $imageId === 'logoBienvenida' || $imageId === 'background') {
                    $imagePath = $this->saveImage($imageData, $webPath, $imageId);
                    $images[$imageId] = $imagePath;
                }
            }
        }

        // Generar HTML
        $html = $this->generateWelcomeHtml($welcomeData, $images);
        file_put_contents($webPath . '/welcome.html', $html);

        // Generar CSS
        $css = $this->generateWelcomeCss($welcomeData);
        file_put_contents($webPath . '/css/welcome.css', $css);

        // Guardar en BD
        Page::create([
            'web_id' => $webId,
            'type' => 'welcome',
            'settings' => json_encode($welcomeData)
        ]);
    }

    private function processContactPage($webId, $data, $webPath)
    {
        $contactData = json_decode($data['contactData'], true);
        
        // Generar HTML
        $html = $this->generateContactHtml($contactData);
        file_put_contents($webPath . '/contact.html', $html);

        // Generar CSS (usamos el mismo CSS de main para consistencia)
        copy($webPath . '/css/main.css', $webPath . '/css/contact.css');

        // Guardar en BD
        Page::create([
            'web_id' => $webId,
            'type' => 'contact',
            'settings' => json_encode($contactData)
        ]);
    }

 

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
        
        $contactItems = '';
        foreach ($contactInfo as $type => $info) {
            if (isset($info['selected']) && $info['selected'] && !empty($info['text'])) {
                $title = ucfirst($type);
                $text = htmlspecialchars($info['text']);
                $contactItems .= '<div class="contact-item">
    <span class="contact-title">' . $title . ':</span>
    <span class="contact-text">' . $text . '</span>
</div>';
            }
        }
        
        $mapSection = '';
        if ($showMap && $mapAddress) {
            $mapSection = '<div class="map-section">
    <h3>Ubicación</h3>
    <div class="map-container">
        <iframe src="https://maps.google.com/maps?q=' . $mapAddress . '&output=embed"></iframe>
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
    
    <main>
        <div class="contact-info">
            <h2>Información de Contacto</h2>
            ' . $contactItems . '
        </div>
        
        ' . $mapSection . '
        
        <div class="contact-form">
            <h3>Envíanos un mensaje</h3>
            <form>
                <div class="form-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Asunto:</label>
                    <input type="text" id="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Mensaje:</label>
                    <textarea id="message" required></textarea>
                </div>
                <button type="submit">Enviar Mensaje</button>
            </form>
        </div>
    </main>
    
    <footer>
        <p>© 2023 Todos los derechos reservados</p>
    </footer>
</body>
</html>';

        return $html;
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
            return $$component;
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
}