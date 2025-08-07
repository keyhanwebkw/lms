<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Keyhanweb\Subsystem\Traits\ApiExeptionHandler;

class Handler extends ExceptionHandler
{
    use ApiExeptionHandler;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Keyhanweb\Subsystem\Exceptions\CustomApiRequestException::class,
        \Keyhanweb\Subsystem\Exceptions\UnauthorizedException::class,
    ];
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Overwrite render for api calls
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }
        return parent::render($request, $exception);
    }
}
