<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetDynamicAssetUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $scheme = $request->getScheme();
        $host = $request->getHost();
        $port = $request->getPort();
        
        $url = $scheme . '://' . $host;
        if ($port && $port !== 80 && $port !== 443) {
            $url .= ':' . $port;
        }
        
        config(['app.url' => $url]);
        config(['services.google.redirect' => trim($url . '/auth/google/callback')]);
        app('url')->forceRootUrl($url);
        
        return $next($request);
    }
}
