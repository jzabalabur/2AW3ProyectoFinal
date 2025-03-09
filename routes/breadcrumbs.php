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
Breadcrumbs::for('usuarios', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard'); 
    $trail->push('Usuarios', route('admin.users.index'));
});
        //CRUD Usuarios
        Breadcrumbs::for('usuarios.show', function (BreadcrumbTrail $trail, $user) {
            $trail->parent('usuarios'); 
            $trail->push('Detalles', route('admin.users.show', $user));
        });
        Breadcrumbs::for('usuarios.edit', function (BreadcrumbTrail $trail, $user) {
            $trail->parent('usuarios'); 
            $trail->push('Editar', route('admin.users.edit', $user));
        });
        Breadcrumbs::for('usuarios.create', function (BreadcrumbTrail $trail) {
            $trail->parent('usuarios'); 
            $trail->push('Crear', route('admin.users.create'));
        });


// Admin > Webs
Breadcrumbs::for('webs', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard'); 
    $trail->push('Webs', route('admin.webs.index'));
});
        //CRUD Webs
        Breadcrumbs::for('webs.show', function (BreadcrumbTrail $trail, $web) {
            $trail->parent('webs'); 
            $trail->push('Detalles', route('admin.webs.show', $web));
        });
        Breadcrumbs::for('webs.edit', function (BreadcrumbTrail $trail, $web) {
            $trail->parent('webs'); 
            $trail->push('Editar', route('admin.webs.edit', $web));
        });
        Breadcrumbs::for('webs.create', function (BreadcrumbTrail $trail) {
            $trail->parent('webs'); 
            $trail->push('Crear', route('admin.webs.create'));
        });