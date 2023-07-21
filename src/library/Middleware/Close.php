<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Middleware;

use PsrPHP\Psr17\Factory;
use PsrPHP\Framework\Config;
use PsrPHP\Framework\Framework;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Close implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        return Framework::execute(function (
            Config $config,
            Factory $factory
        ) use ($request, $handler): ResponseInterface {
            if ($config->get('site.is_close@psrphp.web')) {
                return $factory->createResponse(200)->withBody($factory->createStream($config->get('site.close_reason@psrphp.web', '维护中...')));
            }
            return $handler->handle($request);
        });
    }
}
