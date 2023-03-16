<?php

namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\BaseController;
use app\service\cloud\KfCloud;
use support\Request;

class PluginController extends BaseController
{
    /**
     * 表格列
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-10
     * @param  Request $request
     * @return void
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data = $builder
            ->addActionOptions('操作', [
                'width'         => 180
            ])
            ->addTopButton('cloud', '云服务', [
                'type'          => 'remote',
                'api'           => '/remote/cloud/index',
            ], [
                'title'         => '云服务中心',
            ], [
                'type'          => 'success'
            ])
            ->addRightButton('install', '安装', [
                'api'           => '/Plugin/install',
            ])
            ->addRightButton('uninstall', '卸载', [
                'type'          => 'confirm',
                'api'           => '/Plugin/uninstall',
                'method'        => 'delete',
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认写在该应用插件？',
            ], [
                'type'          => 'danger'
            ])
            ->tabsConfig([
                'active'        => 'install',
                'field'         => 'inst',
                'list'          => [
                    [
                        'label' => '未安装',
                        'value' => 'install',
                    ],
                    [
                        'label' => '已安装',
                        'value' => 'installed',
                    ],
                ]
            ])
            ->addColumn('app_name', '应用名称')
            ->addColumn('version', '应用版本')
            ->addColumn('author', '开发者')
            ->addColumnEle('logo', '应用图标', [
                'params'        => [
                    'type'      => 'image',
                ],
            ])
            ->addColumnEle('type', '应用类型', [
                'params'        => [
                    'type'      => 'tags',
                    'options'   => ['project' => '应用', '' => '插件'],
                    'props'     => [
                        'project'   => [
                            'type'  => 'success'
                        ],
                        'plugin'    => [
                            'type'  => 'danger'
                        ],
                    ],
                ],
            ])
            ->addColumnEle('installed', '应用状态', [
                'width'         => 90,
                'params'        => [
                    'type'      => 'tags',
                    'options'   => ['未安装', '已安装'],
                    'props'     => [
                        [
                            'type'  => 'danger'
                        ],
                        [
                            'type'  => 'success'
                        ],
                    ],
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-10
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $data = KfCloud::getLocalPlugins();
        foreach ($data as &$value) {
            $value['installed'] = 0;
        }
        return parent::successRes($data);
    }

    /**
     * 安装插件
     *
     * @return void
     */
    public function install()
    {
    }

    /**
     * 卸载插件
     *
     * @return void
     */
    public function uninstall()
    {
    }
}
