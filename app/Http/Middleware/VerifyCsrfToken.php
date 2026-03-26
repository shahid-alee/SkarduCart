<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
      protected $except = [
        'stripe/webhook',  // Add this line
        '/stripe/webhook', // Add this line as well for safety
    ];
}
