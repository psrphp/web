<?php

use App\Psrphp\Web\Http\Config\Index;
use App\Psrphp\Web\Http\Page\Index as PageIndex;

return [
    'menus' => [[
        'title' => '网站设置',
        'node' => Index::class
    ], [
        'title' => '页面管理',
        'node' => PageIndex::class
    ]]
];
