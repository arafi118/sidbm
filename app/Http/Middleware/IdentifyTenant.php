<?php

namespace App\Http\Middleware;

use App\Models\Kecamatan;
use Closure;
use Illuminate\Http\Request;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        if (request()->user()) {
            $suffix = request()->user()->lokasi;
            config(['tenant.suffix' => "_" . $suffix]);

            return $next($request);
        }

        $domain = $request->getHost();

        $tenant = Kecamatan::where('web_kec', $domain)->orwhere('web_alternatif', $domain)->first();
        $suffix = $tenant ? "_{$tenant->id}" : '_1';

        config(['tenant.suffix' => $suffix]);
        return $next($request);
    }
}
