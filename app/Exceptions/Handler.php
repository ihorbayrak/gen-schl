<?php

namespace App\Exceptions;

use App\Services\ApiService\Exceptions\ApiConnectionException;
use App\Services\ApiService\Exceptions\ApiRequestException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
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

    public function render($request, Throwable $e)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            if ($e instanceof ApiConnectionException || $e instanceof ApiRequestException) {
                return response()->json([
                    'description' => 'Invalid status value'
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return parent::render($request, $e);
    }
}
