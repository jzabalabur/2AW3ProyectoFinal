@extends('layouts.app')

@section('title', 'Mi Perfil - Zablo')

@push('styles')
<style>
    .perfil-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .user-info {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
    }
    
    .webs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }
    
    .web-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .web-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);
    }
    
    .web-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }
    
    .web-card-body {
        padding: 1.5rem;
        color: white;
    }
    
    .web-card-footer {
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .web-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .web-url {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .web-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    .status-published {
        background: #d4edda;
        color: #155724;
    }
    
    .status-draft {
        background: #fff3cd;
        color: #856404;
    }
    
    .web-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-block;
        text-align: center;
    }
    
    .btn-primary {
        background: #007bff;
        color: white;
    }
    
    .btn-primary:hover {
        background: #0056b3;
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #545b62;
        color: white;
        text-decoration: none;
    }
    
    .btn-success {
        background: #28a745;
        color: white;
    }
    
    .btn-success:hover {
        background: #218838;
        color: white;
        text-decoration: none;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c82333;
        color: white;
        text-decoration: none;
    }
    
    .web-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 1rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .empty-state h3 {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 2rem;
    }
    
    .web-config-info {
        margin-top: 0.5rem;
    }
    
    .badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 500;
        border-radius: 0.25rem;
        margin-right: 0.25rem;
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.3);
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
        background-color: #1f2937;
        color: white;
        margin: 15% auto;
        padding: 20px;
        border-radius: 12px;
        width: 80%;
        max-width: 500px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem;
        border-radius: 8px;
        z-index: 1001;
        min-width: 300px;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
@endpush

@section('content')
<main class="container mx-auto px-6 py-8">
    <div class="perfil-container">
        <!-- Información del usuario -->
        <div class="user-info">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Mi Perfil</h1>
                    <p class="text-white/80 mb-4">Bienvenido, {{ auth()->user()->name }}</p>
                    <p class="text-sm text-white/60">Email: {{ auth()->user()->email }}</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('diseno') }}" class="btn btn-success">
                        + Crear Nueva Web
                    </a>
                </div>
            </div>
        </div>

        <!-- Lista de webs -->
        @if($webs->count() > 0)
            <div class="webs-grid">
                @foreach($webs as $web)
                    <div class="web-card">
                        <div class="web-card-header">
                            <div class="web-title">{{ $web->name }}</div>
                            <div class="web-url">{{ $web->url }}</div>
                        </div>
                        
                        <div class="web-card-body">
                            <span class="web-status {{ $web->is_published ? 'status-published' : 'status-draft' }}">
                                {{ $web->is_published ? 'Publicada' : 'Borrador' }}
                            </span>
                            
                            <div class="web-meta">
                                <span>Creada: {{ $web->created_at->format('d/m/Y') }}</span>
                                <span>Actualizada: {{ $web->updated_at->format('d/m/Y') }}</span>
                            </div>
                            
                            <div class="web-config-info">
                                <small class="text-white/70">
                                    Configuración: 
                                    @if($web->hasWelcomePage())
                                        <span class="badge">Bienvenida</span>
                                    @endif
                                    <span class="badge">Principal</span>
                                    @if($web->hasContactPage())
                                        <span class="badge">Contacto</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="web-card-footer">
                            <div class="web-actions">
                                @if($web->is_published)
                                    <a href="{{ $web->getPublicUrl() }}" target="_blank" class="btn btn-secondary">
                                        Ver Web
                                    </a>
                                @endif
                                
                                <a href="{{ route('webs.edit', $web) }}" class="btn btn-primary">
                                    Editar
                                </a>
                                
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $web->id }}, '{{ $web->name }}')">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>¡Aún no tienes ninguna web!</h3>
                <p>Crea tu primera página web y compártela con el mundo.</p>
                <a href="{{ route('diseno') }}" class="btn btn-success">
                    Crear Mi Primera Web
                </a>
            </div>
        @endif
    </div>
</main>

<!-- Modal de confirmación para eliminar -->
<div id="delete-modal" class="modal">
    <div class="modal-content">
        <h3>¿Estás seguro?</h3>
        <p id="delete-message">Esta acción eliminará permanentemente la web y todos sus datos. No se puede deshacer.</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
            <form id="delete-form" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar Web</button>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif
@endsection

@push('scripts')
<script>
function confirmDelete(webId, webName) {
    document.getElementById('delete-message').textContent = 
        `Esta acción eliminará permanentemente la web "${webName}" y todos sus datos. No se puede deshacer.`;
    
    const form = document.getElementById('delete-form');
    form.action = `/webs/${webId}`;
    
    document.getElementById('delete-modal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('delete-modal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Auto-ocultar alertas después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
});
</script>
@endpush