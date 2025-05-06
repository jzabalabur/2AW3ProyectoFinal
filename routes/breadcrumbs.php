<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

//Home, pagina principal del proyecto
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Admin Dashboard
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Admin Dashboard', route('admin.dashboard'));
});


// Admin > Usuarios
Breadcrumbs::for('admin.usuarios', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Usuarios', route('admin.users.index'));
});
        //CRUD Usuarios
        Breadcrumbs::for('admin.usuarios.create', function (BreadcrumbTrail $trail) {
            $trail->parent('admin.usuarios');
            $trail->push('Crear Usuario', route('admin.users.create'));
        });
        Breadcrumbs::for('admin.usuarios.edit', function (BreadcrumbTrail $trail, $user) {
            $trail->parent('admin.usuarios');
            $trail->push('Editar Usuario', route('admin.users.edit', $user));
        });
        Breadcrumbs::for('admin.usuarios.show', function (BreadcrumbTrail $trail, $user) {
            $trail->parent('admin.usuarios');
            $trail->push('Detalles Usuario', route('admin.users.show', $user));
        });


// Admin > Webs
Breadcrumbs::for('admin.webs', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Webs', route('admin.webs.index'));
});
        //CRUD Webs
        Breadcrumbs::for('admin.webs.create', function (BreadcrumbTrail $trail) {
            $trail->parent('admin.webs');
            $trail->push('Crear Web', route('admin.webs.create'));
        });
        Breadcrumbs::for('admin.webs.edit', function (BreadcrumbTrail $trail, $web) {
            $trail->parent('admin.webs');
            $trail->push('Editar Web', route('admin.webs.edit', $web));
        });
        Breadcrumbs::for('admin.webs.show', function (BreadcrumbTrail $trail, $web) {
            $trail->parent('admin.webs');
            $trail->push('Detalles Web', route('admin.webs.show', $web));
        });

// Webs > Pages
Breadcrumbs::for('pages', function (BreadcrumbTrail $trail, $web) {
    $trail->parent('webs', $web); 
    $trail->push('Páginas', route('admin.webs.pages.index', $web));
});
        //CRUD Webs
        Breadcrumbs::for('pages.show', function (BreadcrumbTrail $trail, $web, $page) {
            $trail->parent('pages', $web);
            $trail->push('Detalles', route('admin.webs.pages.show', ['web' => $web, 'page' => $page]));
        });
        Breadcrumbs::for('pages.edit', function (BreadcrumbTrail $trail, $web, $page) {
            $trail->parent('pages', $web);
            $trail->push('Editar', route('admin.webs.pages.edit', ['web' => $web, 'page' => $page]));
        });
       /* Breadcrumbs::for('pages.create', function (BreadcrumbTrail $trail, $web) {
            $trail->parent('pages', $web);
            $trail->push('Crear', route('admin.webs.pages.create', $web));
        }); */
        
// Cliente Dashboard
Breadcrumbs::for('cliente.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Cliente Dashboard', route('cliente.dashboard'));
});

// Cliente > Webs
Breadcrumbs::for('cliente.webs', function (BreadcrumbTrail $trail) {
    $trail->parent('cliente.dashboard');
    $trail->push('Mis Webs', route('perfil.webs.index'));
});
        //CRUD Webs
        Breadcrumbs::for('cliente.webs.create', function (BreadcrumbTrail $trail) {
            $trail->parent('cliente.webs');
            $trail->push('Crear Web', route('perfil.webs.create'));
        });
        Breadcrumbs::for('cliente.webs.edit', function (BreadcrumbTrail $trail, $web) {
            $trail->parent('cliente.webs');
            $trail->push('Editar Web', route('webs.edit', $web));
        });
        Breadcrumbs::for('cliente.webs.show', function (BreadcrumbTrail $trail, $web) {
            $trail->parent('cliente.webs');
            $trail->push('Detalles Web', route('webs.show', $web));
        });

// Cliente > Perfil
Breadcrumbs::for('cliente.perfil', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Perfil', route('perfil'));
});

