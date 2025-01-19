<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        'ussd/callback',
        'api/*',
        'admin/*',
        'health-check',
        'login',
        'register',
        'password/*'
    ];

    /**
     * The URIs that should be reachable in maintenance mode from selected IPs.
     *
     * @var array<int, string>
     */
    protected $allowedIPs = [
        // Add any IP addresses that should have access during maintenance
        // '127.0.0.1',
        // '192.168.1.1',
    ];
}