<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        // Force Indonesian for all dashboard panels
        if (str_starts_with($path, 'admin') || str_starts_with($path, 'employee') || str_starts_with($path, 'dashboard')) {
            App::setLocale('id');

            return $next($request);
        }

        if ($request->session()->has('locale')) {
            App::setLocale($request->session()->get('locale'));
        }

        return $next($request);
    }
}
