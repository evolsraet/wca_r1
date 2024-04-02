<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

    // 일관된 JSON 응답을 위한 코드 KMH
    public function render($request, Throwable $exception)
    {
        // API 요청인 경우, 일관된 응답 형식으로 처리
        if (1 && $request->expectsJson()) {
            $additional = [];

            // AuthenticationException과 AuthorizationException에 대한 처리
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                $code = Response::HTTP_UNAUTHORIZED;
            } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                $code = Response::HTTP_FORBIDDEN;
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $code = $exception->getStatusCode();
            } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
                $code = Response::HTTP_BAD_REQUEST;
                // $code = 404;
            } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $code = Response::HTTP_NOT_FOUND;
            } else {
                $code = method_exists($exception, 'getCode') && is_int($exception->getCode()) && $exception->getCode() > 0
                    ? $exception->getCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR;
            }

            // if ($code <= 299)
            //     $code = 500;
            $additional['interCode'] = $code;

            $additional['getStatusCode'] = method_exists($exception, 'getStatusCode')
                ? $exception->getStatusCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            $additional['getCode'] = method_exists($exception, 'getCode')
                ? $exception->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR;

            $message = $exception->getMessage();

            // 모델을 찾을 수 없는 경우:
            // if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            //     $message = 'Resource not found';
            //     $code = Response::HTTP_NOT_FOUND;
            // }

            if (method_exists($exception, 'errors'))
                $additional['errors'] = $exception->errors();

            if (config('app.debug')) {
                $additional['debug'] = [
                    'exception class' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => collect($exception->getTrace())->map(function ($trace) {
                        return Arr::except($trace, ['args']); // 'args' 제외
                    })->take(5), // 스택 트레이스의 상위 5개만 포함
                ];
            }


            // return response()->json([$code], $code);
            // 여기서는 모든 예외에 대해 일관된 형식의 응답을 반환합니다.
            return response()->api(null, $message, 'fail', $code, $additional);
        }

        // 기본적인 예외 처리
        return parent::render($request, $exception);
    }
}
