<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
//        if ($exception instanceof HttpException) {
//            if ($exception->getStatusCode() == '404') {
//                return response()->fail(404, 'Not found', null, 404);
//            } else {
//                \App\Utils\Logs::logError('服务器错误!', [$exception->getMessage()]);
//                return response()->fail(500, '服务器错误!', null, 500);
//            }
//        } else if ($exception instanceof AuthenticationException) {
//            return response()->fail(403, '没有权限!', null, 403);
//        } else {
//            \App\Utils\Logs::logError('服务器错误!', [$exception->getMessage()]);
//            return response()->fail(500, '服务器错误!', null, 500);
//        }
    }
}
