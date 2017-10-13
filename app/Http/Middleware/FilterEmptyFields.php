<?php

namespace Portal\Http\Middleware;

use Closure;

class FilterEmptyFields
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        if($input)
        {
            array_walk_recursive($input, function (&$item)
            {
                $item = trim($item);
                $item = ($item == "")? null : $item;
            });

            $request->merge($input);
        }

        return $next($request);
    }
}
