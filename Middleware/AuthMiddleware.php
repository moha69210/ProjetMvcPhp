<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Closure;

class AuthMiddleware
{
    public function __invoke(Request $request, Response $response, Closure $next)
    {
        session_start();
        // Perform authentication logic here

        $authenticated =  $_SESSION['user'];

        if (!$authenticated) {
            // User is not authenticated, return a response indicating authentication failure
            return $response->withStatus(401)->getBody()->write('Authentication failed.');
        }

        // User is authenticated, continue to the next middleware
        return $next($request, $response);
    }
}
