<?php

declare(strict_types=1);

namespace App\Psrphp\Web\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Form\Builder;
use PsrPHP\Form\Col;
use PsrPHP\Form\Fieldset;
use PsrPHP\Form\Html;
use PsrPHP\Form\Row;
use PsrPHP\Form\SwitchItem;
use PsrPHP\Form\Switchs;
use PsrPHP\Form\Cover;
use PsrPHP\Form\Input;
use PsrPHP\Form\Textarea;
use PsrPHP\Framework\Config as FrameworkConfig;
use PsrPHP\Request\Request;
use PsrPHP\Router\Router;

class Config extends Common
{
    public function get(
        Router $router,
        FrameworkConfig $config
    ) {
        $form = new Builder('网站设置');
        $form->addItem(
            (new Row())->addCol(
                (new Col())->addItem(
                    (new Fieldset('基础设置'))->addItem(
                        (new Input('网站名称', 'psrphp[web][site][name]', $config->get('site.name@psrphp.web')))->setHelp('网站标题的后缀，一般不宜过长，例如:PSRPHP'),
                        (new Cover('网站标志', 'psrphp[web][site][logo]', $config->get('site.logo@psrphp.web'), $router->build('/psrphp/admin/tool/upload')))->setHelp('最好不要上传太大的图片~'),
                        (new Switchs('是否关闭网站', 'psrphp[web][site][is_close]', $config->get('site.is_close@psrphp.web', 0)))->addSwitch(
                            (new SwitchItem('开启网站', 0))->addItem(
                                new Html('开启后前台可访问~')
                            ),
                            (new SwitchItem('关闭网站', 1))->addItem(
                                (new Input('关闭原因', 'psrphp[web][site][close_reason]', $config->get('site.close_reason@psrphp.web')))->setHelp('例如：网站维护中...')
                            )
                        ),
                        (new Switchs('是否强制移除url中的index.php', 'psrphp[web][site][hidden_index_file]', $config->get('site.hidden_index_file@psrphp.web', 0)))->addSwitch(
                            (new SwitchItem('不移除', 0))->addItem(
                                new Html('推荐移除~')
                            ),
                            (new SwitchItem('移除', 1))->addItem(
                                new Html('请配置伪静态，否则出现显示页面不存在~')
                            )
                        ),
                    )
                ),
                (new Col)->addItem(
                    (new Fieldset('META信息'))->addItem(
                        (new Input('网站标题', 'psrphp[web][site][title]', $config->get('site.title@psrphp.web')))->setHelp('首页标题，例如：好用的网站管理系统'),
                        (new Input('网站关键词', 'psrphp[web][site][keywords]', $config->get('site.keywords@psrphp.web')))->setHelp('例如：cms psrphp 内容管理系统'),
                        (new Textarea('网站简介', 'psrphp[web][site][description]', $config->get('site.description@psrphp.web')))->setHelp('例如：psrphp是好用的内容管理系统')
                    )
                ),
                (new Col)->addItem(
                    (new Fieldset('其他信息'))->addItem(
                        (new Input('备案号', 'psrphp[web][site][beian]', $config->get('site.beian@psrphp.web')))
                            ->setHelp('例如：京ICP备12345678-1号'),
                        (new Input('联系人电子邮箱', 'psrphp[web][site][email]', $config->get('site.email@psrphp.web')))->setHelp('例如：xxx@xxx.xxx')
                    )
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        FrameworkConfig $config
    ) {
        $config->save('site@psrphp/web', $request->post('psrphp.web.site'));
        return Response::success('更新成功！');
    }
}
