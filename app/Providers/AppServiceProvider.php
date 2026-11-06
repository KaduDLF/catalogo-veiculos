<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer(['layouts.app', 'pages.contato', 'pages.empresa'], fn ($view) => $view->with('loja', \App\Support\Loja::dados()));
    }
}
