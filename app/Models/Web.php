<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Web extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'design_config',
        'welcome_page_data',
        'main_page_data',
        'contact_page_data',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'design_config' => 'array',
        'welcome_page_data' => 'array',
        'main_page_data' => 'array',
        'contact_page_data' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_web');
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    // Verifica si la web tiene página de bienvenida configurada
    public function hasWelcomePage()
    {
        return (isset($this->design_config['welcomeMessage']) && $this->design_config['welcomeMessage']) 
            || !empty($this->welcome_page_data);
    }

    // Verifica si la web tiene página de contacto configurada
    public function hasContactPage()
    {
        return (isset($this->design_config['contactPage']) && $this->design_config['contactPage']) 
            || !empty($this->contact_page_data);
    }

    // Obtiene la configuración de una página específica
    public function getPageData($pageType)
    {
        $column = $pageType . '_page_data';
        return $this->$column ?? [];
    }

    // Actualiza los datos de una página específica
    public function updatePageData($pageType, $data)
    {
        $column = $pageType . '_page_data';
        $this->$column = $data;
        return $this->save();
    }

    // Obtiene la ruta de la web publicada
    public function getPublicUrl()
    {
        return $this->is_published ? url('webs/' . $this->url . '/main.html') : null;
    }
}