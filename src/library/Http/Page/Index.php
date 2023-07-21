<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Http\Page;

use App\Psrphp\Admin\Http\Common;
use PsrPHP\Database\Db;
use PsrPHP\Pagination\Pagination;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Index extends Common
{

    public function get(
        Db $db,
        Pagination $pagination,
        Request $request,
        Template $template
    ) {
        $data = [];
        $where = [];
        $total = $db->count('psrphp_web_page', $where);

        $page = $request->get('page') ?: 1;
        $pagenum = $request->get('pagenum') ?: 100;
        $where['LIMIT'] = [($page - 1) * $pagenum, $pagenum];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];

        $data['datas'] = $db->select('psrphp_web_page', '*', $where);
        $data['total'] = $total;
        $data['pages'] = $pagination->render($page, $total, $pagenum);

        return $template->renderFromFile('page/index@psrphp/web', $data);
    }
}
