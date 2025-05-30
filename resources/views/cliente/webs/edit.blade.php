@extends('layouts.app')

@section('title', 'Editar Web - ' . $web->name)

@push('styles')
<style>
    .edit-web-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .web-info {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
        color: white;
    }
    
    .edit-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .edit-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        color: white;
    }
    
    .edit-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);
    }
    
    .edit-card h3 {
        color: #60a5fa;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.25rem;
    }
    
    .edit-card p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
    
    .edit-btn {
        display: inline-block;
        background: #2563eb;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .edit-btn:hover {
        background: #1d4ed8;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .edit-btn:disabled,
    .edit-btn.disabled {
        background: #6b7280;
        cursor: not-allowed;
        transform: none;
    }
    
    .edit-btn.disabled:hover {
        background: #6b7280;
        transform: none;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-published {
        background: #dcfce7;
        color: #166534;
    }
    
    .status-draft {
        background: #fef3c7;
        color: #92400e;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        margin-bottom: 2rem;
        transition: color 0.2s ease;
    }
    
    .back-link:hover {
        color: #60a5fa;
        text-decoration: none;
    }
    
    .web-actions {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-view {
        background: #059669;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-view:hover {
        background: #047857;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .btn-delete {
        background: #dc2626;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: #1f2937;
        color: white;
        padding: 2rem;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .modal-message {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
    
    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }
    
    .modal-button {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    
    .modal-button.cancel {
        background: #6b7280;
        color: white;
    }
    
    .modal-button.cancel:hover {
        background: #4b5563;
    }
    
    .modal-button.confirm {
        background: #dc2626;
        color: white;
    }
    
    .modal-button.confirm:hover {
        background: #b91c1c;
    }
    /* BOTONES MODERNOS */
.edit-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 16px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transform: translateY(0);
}

.edit-btn:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}

.edit-btn.disabled {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    cursor: not-allowed;
    transform: none;
    box-shadow: 0 2px 8px rgba(156, 163, 175, 0.3);
}

.btn-view {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 16px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

.btn-view:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
}

.btn-delete:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.6);
}

.modal-button {
    padding: 1rem 2rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-button.cancel {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
}

.modal-button.confirm {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}
/* ESPACIADO DE CARDS Y BOTONES */
.edit-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 2rem;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    color: white;
    display: flex;
    flex-direction: column;
    min-height: 200px; /* Altura mínima */
}

.edit-card h3 {
    color: #60a5fa;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.25rem;
}

.edit-card p {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: auto; /* Esto empuja el botón hacia abajo */
    line-height: 1.5;
    flex-grow: 1; /* El párrafo ocupa el espacio disponible */
}

.edit-btn {
    margin-top: 1.5rem; /* Separación del texto */
    align-self: flex-start; /* Alinear al inicio, no estirar */
    /* ... resto de estilos del botón ... */
}
</style>
@endpush

@section('content')
<main class="container mx-auto px-6 py-8">
    <div class="edit-web-container">
        <a href="{{ route('perfil') }}" class="back-link">
            ← Volver al perfil
        </a>
        
        <div class="web-info">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $web->name }}</h1>
                    <p class="text-white/80 mb-4">{{ $web->url }}</p>
                    <span class="status-badge {{ $web->is_published ? 'status-published' : 'status-draft' }}">
                        {{ $web->is_published ? 'Publicada' : 'Borrador' }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-white/60">Creada: {{ $web->created_at->format('d/m/Y') }}</p>
                    <p class="text-sm text-white/60">Actualizada: {{ $web->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
        
        <div class="edit-options">
            <div class="edit-card">
                <h3>
                    ⚙️ Configuración Inicial
                </h3>
                <p>Modifica la configuración básica de tu web: incluir página de bienvenida, página de contacto, etc.</p>
                <a href="{{ route('webs.edit.design', $web) }}" class="edit-btn">
                    Editar Configuración
                </a>
            </div>
            
            <div class="edit-card">
                <h3>
                    👋 Página de Bienvenida
                </h3>
                <p>Personaliza la página de bienvenida: textos, colores, logo, fondo, etc.</p>
                @if($web->hasWelcomePage())
                    <a href="{{ route('webs.edit.welcome', $web) }}" class="edit-btn">
                        Editar Bienvenida
                    </a>
                @else
                    <span class="edit-btn disabled">
                        No configurada
                    </span>
                @endif
            </div>
            
            <div class="edit-card">
                <h3>
                    🏠 Página Principal
                </h3>
                <p>Edita el contenido principal de tu web: textos, imágenes, módulos, etc.</p>
                <a href="{{ route('webs.edit.main', $web) }}" class="edit-btn">
                    Editar Principal
                </a>
            </div>
            
            <div class="edit-card">
                <h3>
                    📞 Página de Contacto
                </h3>
                <p>Configura la información de contacto y el mapa de ubicación.</p>
                @if($web->hasContactPage())
                    <a href="{{ route('webs.edit.contact', $web) }}" class="edit-btn">
                        Editar Contacto
                    </a>
                @else
                    <span class="edit-btn disabled">
                        No configurada
                    </span>
                @endif
            </div>
        </div>
        
        <div class="web-actions">
            <h3 class="text-xl font-semibold mb-4">Acciones de Web</h3>
            <div class="action-buttons">
                @if($web->is_published)
                    <a href="{{ $web->getPublicUrl() }}" target="_blank" class="btn-view">
                        Ver Web Publicada
                    </a>
                @endif
                
                <button type="button" class="btn-delete" onclick="confirmDelete()">
                    Eliminar Web
                </button>
            </div>
        </div>
    </div>
</main>

<!-- Modal de confirmación para eliminar -->
<div id="delete-modal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">¿Estás seguro?</h3>
        <p class="modal-message">Esta acción eliminará permanentemente la web "{{ $web->name }}" y todos sus datos. No se puede deshacer.</p>
        <div class="modal-actions">
            <button id="cancel-delete" class="modal-button cancel">Cancelar</button>
            <form id="delete-form" action="{{ route('webs.destroy', $web) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-button confirm">Eliminar Web</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    document.getElementById('delete-modal').style.display = 'flex';
}

document.getElementById('cancel-delete').onclick = function() {
    document.getElementById('delete-modal').style.display = 'none';
};

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('delete-modal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};
</script>
@endpush