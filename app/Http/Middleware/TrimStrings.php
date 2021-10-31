<?php

namespace App\Http\Middleware;

use Attla\Middleware\TrimStrings as BaseTrimmer;

class TrimStrings extends BaseTrimmer
{
    /**
     * The names of the attributes that should not be trimmed
     *
     * @var array
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * The characters to be trimmed.
     *
     * @var string
     */
    protected $characters = " \t\n\r\0\x0B\xc2\xa0";
}
