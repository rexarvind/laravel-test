<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Closure;

class HttpsProtocol {
    public function handle($request, Closure $next) {
        if ( App::environment(['staging', 'production']) && !$request->secure() ) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
