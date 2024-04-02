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
        // 'api/*', 'login','register'
    ];

    public function __construct(\Illuminate\Foundation\Application $app, \Illuminate\Contracts\Encryption\Encrypter $encrypter)
    {
        parent::__construct($app, $encrypter);
        // 환경 변수나 애플리케이션의 설정을 기반으로 조건을 추가
        if (app()->environment('local') && str_contains(request()->header('Referer'), '/api-docs')) {
            $this->except = ['api/*', 'login', 'logout', 'register'];
        }
    }
}
