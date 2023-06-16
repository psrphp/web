<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Http\Page;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        $db->delete('psrphp_web_page', [
            'id' => $request->get('id'),
        ]);
        return Response::success('操作成功！');
    }
}
