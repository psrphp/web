<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Middleware;

use PsrPHP\Database\Db;
use PsrPHP\Psr17\Factory;
use PsrPHP\Template\Template;
use PsrPHP\Framework\Framework;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PsrPHP\Framework\Config;

class Page implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $res = $handler->handle($request);
        if ($res->getStatusCode() != '404') {
            return $res;
        }

        return Framework::execute(function (
            Template $template,
            Factory $factory,
            Db $db,
            Config $config
        ) use ($res): ResponseInterface {
            $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            if (strlen($_SERVER['SCRIPT_NAME']) > strlen($path)) {
                $dir = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
                if (strpos($path, $dir) === 0) {
                    $path = substr($path, strlen($dir));
                }
            } else {
                if (strpos($path, $_SERVER['SCRIPT_NAME']) === 0) {
                    $path = substr($path, strlen($_SERVER['SCRIPT_NAME']));
                }
            }

            if ($page = $db->get('psrphp_web_page', '*', [
                'page' => $path == '' ? '/' : $path,
            ])) {
                if ($config->get('site.is_close@psrphp.web')) {
                    return $factory->createResponse(200)->withBody($factory->createStream($config->get('site.close_reason@psrphp.web', '维护中...')));
                } else {
                    $response = $factory->createResponse();
                    $response->getBody()->write($template->renderFromString($page['tpl']));
                    return $response;
                }
            }
            return $res;
        });
    }
}
