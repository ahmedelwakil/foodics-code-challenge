<?php

namespace App\Exceptions;

use App\Utils\HttpStatusCodeUtil;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
        EntityNotFoundException::class,
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

    public function render($request, Throwable $exception)
    {
        if (in_array(get_class($exception), $this->dontReport))
            return response()->json(['payload' => ['message' => $exception->getMessage()]], HttpStatusCodeUtil::BAD_REQUEST, [], JSON_INVALID_UTF8_IGNORE);
        return response()->json(['payload' => ['message' => 'Something Went Wrong!']], HttpStatusCodeUtil::SERVER_ERROR, [], JSON_INVALID_UTF8_IGNORE);
    }
}
