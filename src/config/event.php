<?php

use App\Psrphp\Web\Http\Common;
use App\Psrphp\Web\Middleware\Close;
use PsrPHP\Framework\Handler;

return [
    Common::class => [
        function (
            Handler $handler
        ) {
            $handler->pushMiddleware(Close::class);
        }
    ],
];
