<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Http\Page;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Database\Db;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Component\Col;
use PsrPHP\Form\Component\Row;
use PsrPHP\Form\Field\Code;
use PsrPHP\Form\Field\Input;
use PsrPHP\Form\Field\Radio;
use PsrPHP\Request\Request;

class Create extends Common
{
    public function get()
    {
        $form = new Builder('添加页面');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Input('页面', 'page'))->set('help', '例如：/, /help, /about.html, /page/map.php'),
                    (new Code('模板', 'tpl'))->set('help', '支持模板标签'),
                    new Radio('是否发布', 'state', 1, [
                        '1' => '是',
                        '0' => '否',
                    ]),
                    new Input('备注', 'tips')
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $db->insert('psrphp_web_page', [
            'page' => $request->post('page'),
            'tpl' => $request->post('tpl'),
            'state' => $request->post('state', 1, ['intval']),
            'tips' => $request->post('tips'),
        ]);
        return Response::success('操作成功！', 'javascript:history.go(-2)');
    }
}
