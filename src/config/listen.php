<?php

use App\Psrphp\Admin\Model\MenuProvider;
use App\Psrphp\Web\Http\Config;
use PsrPHP\Framework\RequestHandler;

return [
    RequestHandler::class => function (
        RequestHandler $requestHandler
    ) {
        $requestHandler->pushMiddleware(Close::class);
        if ($config->get('site.hidden_index_file@psrphp.web', 0)) {
            $handler->pushMiddleware(RemoveIndexFilePath::class);
        }
    },
    MenuProvider::class => function (
        MenuProvider $menuProvider
    ) {
        $menuProvider->add('网站设置', Config::class);
    },
];
