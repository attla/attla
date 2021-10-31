<?php

namespace App\Http\Middleware;

use Attla\Middleware\Csrf;

class VerifyCsrfToken extends Csrf
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // '',
    ];
}
