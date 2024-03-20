<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Psrphp;

use App\Psrphp\Admin\Model\MenuProvider;
use App\Psrphp\Web\Http\Common;
use App\Psrphp\Web\Http\Config as HttpConfig;
use App\Psrphp\Web\Middleware\Close;
use App\Psrphp\Web\Middleware\RemoveIndexFilePath;
use PsrPHP\Framework\Config;
use PsrPHP\Framework\Framework;
use PsrPHP\Framework\Handler;
use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{
    public function getListenersForEvent(object $event): iterable
    {
        if (is_a($event, Common::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    Handler $handler,
                    Config $config
                ) {
                    $handler->pushMiddleware(Close::class);
                    if ($config->get('site.hidden_index_file@psrphp.web', 0)) {
                        $handler->pushMiddleware(RemoveIndexFilePath::class);
                    }
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
                    $provider->add('网站设置', HttpConfig::class);
                }, [
                    MenuProvider::class => $event,
                ]);
            };
        }
    }
}
