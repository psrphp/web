<?php

declare(strict_types=1);

namespace App\Psrphp\Page\PsrPHP;

use App\Psrphp\Page\Middleware\Page;
use PsrPHP\Psr15\RequestHandler;

class Hook
{
    public static function onStart(
        RequestHandler $requestHandler,
        Page $page
    ) {
        $requestHandler->appendMiddleware($page);
    }
}
