<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => false,
                'message' => 'Usuário não encontrado!',
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
