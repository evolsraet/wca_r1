<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * 유효성 검사 예외에 대한 JSON 응답 처리
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->api(
            null,
            '입력한 정보에 오류가 있습니다.',
            'fail',
            422,
            ['errors' => $exception->errors()]
        );
    }

    /**
     * API 요청에 대한 일관된 예외 처리
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            // 유효성 검사 예외
            if ($exception instanceof ValidationException) {
                return $this->invalidJson($request, $exception);
            }

            // 인증 예외
            if ($exception instanceof AuthenticationException) {
                return response()->api([], '인증이 필요합니다.', 'fail', 401);
            }

            // 권한 예외
            if ($exception instanceof AuthorizationException) {
                return response()->api([], '접근 권한이 없습니다.', 'fail', 403);
            }

            // 모델 없음 예외
            if ($exception instanceof ModelNotFoundException) {
                return response()->api([], '요청한 데이터를 찾을 수 없습니다.', 'fail', 404);
            }

            // HTTP 예외
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $code = $exception->getStatusCode();
                $message = $exception->getMessage() ?: '요청을 처리할 수 없습니다.';
                return response()->api([], $message, 'fail', $code);
            }

            // 일반 예외 처리
            $message = config('app.debug') ? $exception->getMessage() : '서버에서 오류가 발생했습니다.';
            $code = method_exists($exception, 'getCode') && $exception->getCode() > 0 
                ? $exception->getCode() 
                : 500;

            // 디버그 정보 추가 (개발 환경에서만)
            $additional = [];
            if (config('app.debug')) {
                $additional['debug'] = [
                    'exception' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => collect($exception->getTrace())
                        ->map(fn($trace) => Arr::except($trace, ['args']))
                        ->take(5)
                ];
            }

            return response()->api([], $message, 'fail', $code, $additional);
        }

        return parent::render($request, $exception);
    }
}
