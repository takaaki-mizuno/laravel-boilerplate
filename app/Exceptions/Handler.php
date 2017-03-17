<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->is('api/*')) {
            if( !$exception->getMessage() ) {
                switch( $exception->getStatusCode() ) {
                    // not authorized
                    case '403':
                        return response()->json(['code' => 101, 'message' => config('api.messages.101'), 'data' => null]);
                        break;

                    // not found
                    case '404':
                        return response()->json(['code' => 109, 'message' => config('api.messages.109'), 'data' => null]);
                        break;

                    // wrong http method
                    case '405':
                        return response()->json(['code' => 108, 'message' => config('api.messages.108'), 'data' => null]);
                        break;

                    // internal error
                    case '500':
                        return response()->json(['code' => 905, 'message' => config('api.messages.905'), 'data' => null]);
                        break;

                    default:
                        return $this->renderHttpException($exception);
                        break;
                }
            } else {
                // defined in route but method not exist
                return response()->json(['code' => 110, 'message' => $exception->getMessage(), 'data' => null]);
            }
        }
        
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
