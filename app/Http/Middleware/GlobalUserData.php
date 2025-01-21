<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class GlobalUserData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Retrieve session data for username, role, name, and ic_no
        $username = session('username', 'Guest');
        $role = session('role', 'Guest');
        $imageUrl = session('imageUrl', 'https://www.w3schools.com/howto/img_avatar.png'); // Default avatar
        $name = session('name', 'Guest User');
        $ic_no = session('ic_no', 'Not Provided');

        // Share data with all views
        View::share('username', $username);
        View::share('role', $role);
        View::share('imageUrl', $imageUrl);
        View::share('name', $name);
        View::share('ic_no', $ic_no);

        return $next($request);
    }
}
