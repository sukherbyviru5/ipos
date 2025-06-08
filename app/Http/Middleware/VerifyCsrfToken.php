<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'integrate/create-siswa',
        'integrate/status-cbt',
        'integrate/detail-cbt',
        'integrate/delete-siswa'
    ];
}
