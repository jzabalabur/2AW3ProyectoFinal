<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('webs', function (Blueprint $table) {
            // Configuración de diseño general
            $table->json('design_config')->nullable()->after('url');
            
            // Datos de la página de bienvenida
            $table->json('welcome_page_data')->nullable()->after('design_config');
            
            // Datos de la página principal
            $table->json('main_page_data')->nullable()->after('welcome_page_data');
            
            // Datos de la página de contacto
            $table->json('contact_page_data')->nullable()->after('main_page_data');
            
            // Estado de publicación
            $table->boolean('is_published')->default(false)->after('contact_page_data');
            $table->timestamp('published_at')->nullable()->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webs', function (Blueprint $table) {
            $table->dropColumn([
                'design_config',
                'welcome_page_data', 
                'main_page_data',
                'contact_page_data',
                'is_published',
                'published_at'
            ]);
        });
    }
};