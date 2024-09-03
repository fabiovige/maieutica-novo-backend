<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // NotFoundHttpException
        $exceptions->render(function(NotFoundHttpException $e, Request $request){
            if($e->getPrevious() instanceof ModelNotFoundException){
                return response()->json([
                    'status' => false,
                    'message' => 'Registro não encontrado!'
                ], 404);
            }
        });

        // AuthenticationException
        $exceptions->render(function(AuthenticationException $e){
            
            return response()->json([
                'status' => false,
                'message' => 'Credenciais incorretas!'
            ], 404);
        
        });
    })->create();
