<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
//Home, pagina principal del proyecto
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});
// Admin Dashboard
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home'); // Inherit from the 'home' breadcrumb
    $trail->push('Admin Dashboard', route('admin.dashboard'));
});

// Admin > Usuarios
Breadcrumbs::for('usuarios', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard'); // Inherit from the 'admin.dashboard' breadcrumb
    $trail->push('Usuarios', route('usuarios'));
});

// Admin > Webs
Breadcrumbs::for('webs', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard'); // Inherit from the 'admin.dashboard' breadcrumb
    $trail->push('Webs', route('webs'));
});