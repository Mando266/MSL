<?php

namespace App\Http\Middleware;

use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use Closure;

class LessorMiddleware
{
    public function handle($request, Closure $next)
    {
//        if (auth()->check()) {
//            $lessor_id = (int)auth()->user()->lessor_id;
//
//            if ($lessor_id != 0) {
//                session(['lessorId' => $lessor_id]);
//            }
//        }
//        $lessor_id = session('lessorId') ?? 0;

        if (auth()->check()) {
            $lessor_id = (int)auth()->user()->lessor_id;

            if ($lessor_id != 0) {
                Movements::addGlobalScope('lessor', function ($builder) use ($lessor_id) {
                    $builder->whereHas('container', function ($q) use ($lessor_id) {
                        $q->where('description', $lessor_id);
                    });
                });
                Containers::addGlobalScope('lessor', function ($builder) use ($lessor_id) {
                    $builder->where('description', $lessor_id);
                });
            }
        }

        return $next($request);
    }
}
