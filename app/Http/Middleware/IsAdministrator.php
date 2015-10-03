<?php

namespace web1\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Closure;

class IsAdministrator
{
  public function __construct(Guard $auth) {
    $this->auth = $auth;
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
    return ($this->auth->check() 
      && $this->auth->user()->is_admin)
      ? $next($request) // authorised
      : (($request->ajax()) // unauthorised...
        ? response('Forbidden.', 403) //    ... Async
        : redirect()->guest('auth/login')); // ... Sync                 
  }
}
