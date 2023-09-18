<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Middleware;

use App\Psrphp\Admin\Lib\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RemoveIndexFilePath implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if (strpos($request->getRequestTarget(), $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = $request->getUri()->__toString();
            $target = $request->getRequestTarget();
            $base = substr($uri, 0, -strlen($target));
            $newtarget = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')) . substr($request->getRequestTarget(), strlen($_SERVER['SCRIPT_NAME']));
            return Response::redirect($base . $newtarget, 301);
        }
        return $handler->handle($request);
    }
}
