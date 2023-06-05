<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Component\Html;
use PsrPHP\Form\Component\SwitchItem;
use PsrPHP\Form\Component\Switchs;
use PsrPHP\Form\Component\Tab;
use PsrPHP\Form\Component\TabItem;
use PsrPHP\Form\Field\Cover;
use PsrPHP\Form\Field\Input;
use PsrPHP\Form\Field\Textarea;
use PsrPHP\Request\Request;
use PsrPHP\Router\Router;
use PsrPHP\Framework\Config;

class Set extends Common
{
    public function get(
        Router $router,
        Config $config
    ) {
        $form = new Builder('网站设置');
        $form->addItem(
            (new Tab())->addTab(
                (new TabItem('基础设置'))->addItem(
                    (new Input('网站名称', 'psrphp[web][site][name]', $config->get('site.name@psrphp.web')))->set('help', '网站标题的后缀，一般不宜过长，例如:EBCMS'),
                    (new Cover('网站标志', 'psrphp[web][site][logo]', $config->get('site.logo@psrphp.web'), $router->build('/psrphp/admin/upload')))->set('help', '最好不要上传太大的图片~'),
                    (new Switchs('是否关闭网站', 'psrphp[web][site][is_close]', $config->get('site.is_close@psrphp.web', 0)))->addSwitch(
                        (new SwitchItem('开启网站', 0))->addItem(
                            new Html('开启后前台可访问~')
                        ),
                        (new SwitchItem('关闭网站', 1))->addItem(
                            (new Input('关闭原因', 'psrphp[web][site][close_reason]', $config->get('site.close_reason@psrphp.web')))->set('help', '例如：网站维护中...')
                        )
                    ),
                    (new Input('备案号', 'psrphp[web][site][beian]', $config->get('site.beian@psrphp.web')))
                        ->set('help', '例如：京ICP备12345678-1号'),
                    (new Input('联系人电子邮箱', 'psrphp[web][site][email]', $config->get('site.email@psrphp.web')))->set('help', '例如：xxx@xxx.xxx')
                ),
                (new TabItem('META信息'))->addItem(
                    (new Input('网站标题', 'psrphp[web][site][title]', $config->get('site.title@psrphp.web')))->set('help', '首页标题，例如：好用的网站管理系统'),
                    (new Input('网站关键词', 'psrphp[web][site][keywords]', $config->get('site.keywords@psrphp.web')))->set('help', '例如：cms psrphp 内容管理系统'),
                    (new Textarea('网站简介', 'psrphp[web][site][description]', $config->get('site.description@psrphp.web')))->set('help', '例如：psrphp是好用的内容管理系统')
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Config $config
    ) {
        $config->save('site@psrphp/web', $request->post('psrphp.web.site'));
        return Response::success('更新成功！');
    }
}
