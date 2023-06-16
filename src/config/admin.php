<?php

use App\Psrphp\Admin\Model\Account;
use App\Psrphp\Web\Http\Config\Index;
use App\Psrphp\Web\Http\Page\Index as PageIndex;
use PsrPHP\Router\Router;
use PsrPHP\Framework\Framework;

return [
    'menus' => Framework::execute(function (
        Account $account,
        Router $router
    ): array {
        $menus = [];
        if ($account->checkAuth(Index::class)) {
            $menus[] = [
                'title' => '网站设置',
                'url' => $router->build('/psrphp/web/config/index'),
            ];
        }
        if ($account->checkAuth(PageIndex::class)) {
            $menus[] = [
                'title' => '页面管理',
                'url' => $router->build('/psrphp/web/page/index'),
            ];
        }
        return $menus;
    })
];
