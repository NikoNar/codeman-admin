<?php

namespace Codeman\Admin\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Codeman\Admin\Models\User as AdminUser;
use Spatie\Permission\Models\Role;

class Admin
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd(auth()->guard('admin'));
        if (auth()->guard('admin')->check()) {
//            if (count(AdminUser::where('email', auth()->user()->email)->whereHas('roles', function($query){
//                $query->whereIn('title', ['SuperAdmin', 'Admin', 'Editor']);
//            })->get()) )
            if(auth()->user()->hasAnyRole(Role::all()))
            {
            return $next($request);
            }
        }
        return redirect('/');
    }

}
