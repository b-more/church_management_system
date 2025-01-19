<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

class ValidateSignature extends Middleware
{
    /**
     * The names of the parameters that are used to calculate the signature.
     *
     * @var array<int, string>
     */
    protected $parameters = [
        'expires',
        'id',
        'signature',
    ];

    /**
     * The names of the query string parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $except = [
        'fbclid',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_source',
        'utm_term',
        'page',
        'items',
        'sort',
    ];
}