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
use PsrPHP\Form\Field\Hidden;
use PsrPHP\Form\Field\Input;
use PsrPHP\Form\Field\Radio;
use PsrPHP\Request\Request;

class Update extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        $version = $db->get('psrphp_web_page', '*', [
            'id' => $request->get('id', 0, ['intval']),
        ]);
        $form = new Builder('编辑页面');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-8'))->addItem(
                    new Hidden('id', $version['id']),
                    new Input('页面', 'page', $version['page']),
                    new Code('说明', 'content', $version['content']),
                    new Radio('是否发布', 'state', $version['state'], [
                        '1' => '是',
                        '0' => '否',
                    ]),
                    new Input('备注', 'tips', $version['tips'])
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $version = $db->get('psrphp_web_page', '*', [
            'id' => $request->post('id', 0, ['intval']),
        ]);

        $update = array_intersect_key($request->post(), [
            'page' => '',
            'content' => '',
            'state' => '',
            'tips' => '',
        ]);

        $db->update('psrphp_web_page', $update, [
            'id' => $version['id'],
        ]);

        return Response::success('操作成功！');
    }
}
