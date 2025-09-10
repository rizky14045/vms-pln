<?php

namespace App\Exceptions;

use Throwable;
use App\Helper\ResponseHelper;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            
            // Kalau Web (non-API, form biasa)
            return redirect()->back()
                ->withErrors(['error' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.'])
                ->withInput();
            
        }

        return parent::render($request, $exception);
    }
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
