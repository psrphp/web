<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Psrphp;

use App\Psrphp\Admin\Model\MenuProvider;
use App\Psrphp\Web\Http\Common;
use App\Psrphp\Web\Http\Config;
use App\Psrphp\Web\Middleware\Close;
use Psr\EventDispatcher\ListenerProviderInterface;
use PsrPHP\Framework\Framework;
use PsrPHP\Framework\Handler;

class ListenerProvider implements ListenerProviderInterface
{
    public function getListenersForEvent(object $event): iterable
    {
        if (is_a($event, Common::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    Handler $handler
                ) {
                    $handler->pushMiddleware(Close::class);
                }, [
                    Common::class => $event,
                ]);
            };
        }
        if (is_a($event, MenuProvider::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    MenuProvider $provider
                ) {
                    $provider->add('网站设置', Config::class);
                }, [
                    MenuProvider::class => $event,
                ]);
            };
        }
    }
}
