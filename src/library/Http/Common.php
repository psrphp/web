<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Http;

use App\Psrphp\Admin\Traits\RestfulTrait;
use App\Psrphp\Web\Middleware\Close;
use PsrPHP\Psr15\RequestHandler;

abstract class Common
{
    use RestfulTrait;

    public function __construct(
        RequestHandler $requestHandler,
        Close $closeMiddleware
    ) {
        $requestHandler->appendMiddleware($closeMiddleware);
    }
}
