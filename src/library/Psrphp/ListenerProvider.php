<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Psrphp;

use App\Psrphp\Admin\Model\MenuProvider;
use App\Psrphp\Web\Http\Common;
use App\Psrphp\Web\Http\Config;
use App\Psrphp\Web\Middleware\Close;
use PsrPHP\Framework\Handler;
use PsrPHP\Framework\Listener;

class ListenerProvider extends Listener
{
    public function __construct()
    {
        $this->add(Common::class, function (
            Handler $handler
        ) {
            $handler->pushMiddleware(Close::class);
        });

        $this->add(MenuProvider::class, function (
            MenuProvider $provider
        ) {
            $provider->add('网站设置', Config::class);
        });
    }
}
