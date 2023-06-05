<?php

use App\Psrphp\Admin\Model\Account;
use App\Psrphp\Web\Http\Set;
use PsrPHP\Router\Router;
use PsrPHP\Framework\Framework;

return [
    'menus' => Framework::execute(function (
        Account $account,
        Router $router
    ): array {
        $menus = [];
        if ($account->checkAuth(Set::class)) {
            $menus[] = [
                'title' => '网站设置',
                'url' => $router->build('/psrphp/web/set'),
            ];
        }
        $menus[] = [
            'title' => '访问首页',
            'url' => $router->build('/'),
        ];
        return $menus;
    })
];
