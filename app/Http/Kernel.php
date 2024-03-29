<?php

namespace App\Http;

use Attla\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack
     *
     * These middleware are run during every request to your application
     *
     * @var string[]
     */
    public $middleware = [
        \Attla\Middleware\Cors::class,
        \Attla\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \App\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\XssProtection::class,
    ];

    /**
     * The application's route middleware groups
     *
     * @var array
     */
    public $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Attla\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware
     *
     * These middleware may be assigned to groups or used individually
     *
     * @var array
     */
    public $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticated::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];

    /**
     * The application's service providers
     *
     * @var string[]
     */
    public $providers = [
        //
    ];
}
