<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Psrphp;

use App\Psrphp\Admin\Model\MenuProvider;
use App\Psrphp\Web\Http\Common;
use App\Psrphp\Web\Http\Config as HttpConfig;
use App\Psrphp\Web\Middleware\Close;
use PsrPHP\Database\Db;
use PsrPHP\Framework\Config;
use PsrPHP\Framework\Framework;
use PsrPHP\Framework\Handler;
use PsrPHP\Psr11\Container;
use PsrPHP\Request\Request;
use PsrPHP\Router\Router;
use PsrPHP\Session\Session;
use PsrPHP\Template\Template;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

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
            yield function () use ($event) {
                Framework::execute(function (
                    Container $container
                ) {
                    $container->set(Template::class, function (
                        Db $db,
                        Router $router,
                        Config $config,
                        Session $session,
                        Request $request,
                        Template $template,
                        Container $container,
                        CacheInterface $cache,
                        LoggerInterface $logger,
                    ) {
                        $template->assign([
                            'db' => $db,
                            'cache' => $cache,
                            'session' => $session,
                            'logger' => $logger,
                            'router' => $router,
                            'config' => $config,
                            'request' => $request,
                            'template' => $template,
                            'container' => $container,
                        ]);
                        $template->extend('/\{cache\s*(.*)\s*\}([\s\S]*)\{\/cache\}/Ui', function ($matchs) {
                            $params = array_filter(explode(',', trim($matchs[1])));
                            if (!isset($params[0])) {
                                $params[0] = 3600;
                            }
                            if (!isset($params[1])) {
                                $params[1] = 'tpl_extend_cache_' . md5($matchs[2]);
                            }
                            return '<?php echo call_user_func(function($args){
                                extract($args);
                                if (!$cache->has(\'' . $params[1] . '\')) {
                                    $res = $template->renderFromString(base64_decode(\'' . base64_encode($matchs[2]) . '\'), $args, \'__' . $params[1] . '\');
                                    $cache->set(\'' . $params[1] . '\', $res, ' . $params[0] . ');
                                }else{
                                    $res = $cache->get(\'' . $params[1] . '\');
                                }
                                return $res;
                            }, get_defined_vars());?>';
                        });
                        return $template;
                    });
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
