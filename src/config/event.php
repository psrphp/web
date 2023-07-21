<?php

use App\Psrphp\Web\Middleware\Page;
use PsrPHP\Framework\Handler;

return [
    Handler::class => [
        function (
            Handler $handler
        ) {
            $handler->pushMiddleware(Page::class);
        },
    ],
];
