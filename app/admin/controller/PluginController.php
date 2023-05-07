<?php

namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\admin\model\Store;
use app\admin\service\kfcloud\CloudService;
use app\BaseController;
use app\enum\PlatformTypes;
use app\enum\PlatformTypesStyle;
use app\enum\PluginType;
use app\enum\PluginTypeStyle;
use app\model\SystemPlugin;
use support\Request;

/**
 * 插件管理
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-05
 */
class PluginController extends BaseController
{
    /**
     * 模型
     * @var Store
     */
    protected $model;

    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function __construct()
    {
        $this->model = new SystemPlugin;
    }

    /**
     * 表格
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data    = $builder
            ->addActionOptions('操作', [
                'width' => 230
            ])
            ->pageConfig()
            ->addTopButton(
                'cloud',
                '云服务',
                [
                    'type'  => 'remote',
                    'modal' => true,
                    'api'   => 'admin/PluginCloud/index',
                    'path'  => 'remote/cloud/index'
                ],
                [
                    'title' => '云服务中心',
                    'width' => '450px'
                ],
                [
                    'type' => 'success'
                ]
            )
            ->addRightButton(
                'doc',
                '文档',
                [
                    'type'        => 'link',
                    'api'         => 'admin/Plugin/getDoc',
                    'aliasParams' => [
                        'name'
                    ],
                ],
                [],
                [
                    'type' => 'warning'
                ]
            )
            ->addRightButton(
                'buy',
                '购买',
                [
                    'type'        => 'remote',
                    'modal'       => true,
                    'api'         => 'admin/Plugin/buy',
                    'path'        => 'remote/cloud/buy',
                    'aliasParams' => [
                        'name'
                    ],
                    'params'      => [
                        'field' => 'plugin_status',
                        'value' => 'buy',
                    ]
                ],
                [
                    'title' => '购买应用',
                    'width' => '45%',
                ],
                [
                    'type' => 'success'
                ]
            )
            ->addRightButton(
                'install',
                '安装',
                [
                    'type'   => 'remote',
                    'modal'  => true,
                    'api'    => 'admin/Plugin/install',
                    'path'   => 'remote/cloud/install',
                    'aliasParams' => [
                        'name'
                    ],
                    'params' => [
                        'field' => 'plugin_status',
                        'value' => 'install',
                    ]
                ],
                [
                    'title' => '应用安装',
                    'width' => '45%',
                ],
                [
                    'type' => 'success'
                ]
            )
            ->addRightButton(
                'update',
                '更新',
                [
                    'type'   => 'remote',
                    'modal'  => true,
                    'api'    => 'admin/Plugin/update',
                    'path'   => 'remote/cloud/update',
                    'aliasParams' => [
                        'name'
                    ],
                    'params' => [
                        'field' => 'plugin_status',
                        'value' => 'update',
                    ]
                ],
                [
                    'title' => '应用安装',
                    'width' => '45%',
                ],
                [
                    'type' => 'success'
                ]
            )
            ->addRightButton(
                'uninstall',
                '卸载',
                [
                    'type'   => 'confirm',
                    'api'    => 'admin/Plugin/uninstall',
                    'path'   => 'remote/cloud/uninstall',
                    'method' => 'delete',
                    'params' => [
                        'field' => 'plugin_status',
                        'value' => 'uninstall',
                    ]
                ],
                [
                    'title'   => '温馨提示',
                    'content' => '是否确认写在该应用插件？',
                ],
                [
                    'type' => 'danger'
                ]
            )
            ->tabsConfig([
                'active' => '',
                'field'  => 'plugin',
                'list'   => [
                    [
                        'label' => '全部',
                        'value' => '',
                    ],
                    [
                        'label' => '未安装',
                        'value' => '0',
                    ],
                    [
                        'label' => '已安装',
                        'value' => '1',
                    ],
                ]
            ])
            ->addColumn('title', '应用名称')
            ->addColumn('version', '应用版本', [
                'width' => 100
            ])
            ->addColumn('dev.title', '开发者')
            ->addColumnEle('money', '应用价格', [
                'params' => [
                    'type' => 'money'
                ]
            ])
            ->addColumnEle('logo', '应用图标', [
                'width'  => 100,
                'params' => [
                    'type' => 'image',
                ],
            ])
            ->addColumnEle('platform', '平台类型', [
                'width'  => 120,
                'params' => [
                    'type'    => 'tags',
                    'options' => PlatformTypes::dictOptions(),
                    'style'=> PlatformTypesStyle::parseAlias('type'),
                ],
            ])
            ->addColumnEle('plugin_type', '应用类型', [
                'width'  => 100,
                'params' => [
                    'type'    => 'tags',
                    'options' => PluginType::dictOptions(),
                    'style'   => PluginTypeStyle::parseAlias('type'),
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function index(Request $request)
    {
        // 分页
        $page     = (int) $request->get('page', 1);
        // 应用状态：空值为全部，0未安装，1已安装
        $plugin     = (int) $request->get('plugin', '');
        $response = CloudService::list($page)->array();
        if (!$response) {
            return $this->successRes([]);
        }
        if ($response['code'] !== 200) {
            return $this->successRes([]);
        }
        $data = $this->getPluginList($response['data']);
        return parent::successRes($data);
    }

    /**
     * 获取文档地址
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public function getDoc(Request $request)
    {
        $name = $request->get('name');
        $detail = CloudService::detail($name)->array();
        if (!$detail) {
            return $this->fail('获取应用失败');
        }
        if ($detail['code'] !== 200) {
            return json($detail);
        }
        if (!isset($detail['data']['doc_url'])) {
            return $this->fail('获取文档地址失败');
        }
        return $this->successRes(['url' => $detail['data']['doc_url']]);
    }

    /**
     * 购买应用
     * @param Request $request
     * @return \yzh52521\EasyHttp\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public function buy(Request $request)
    {
        $name = $request->get('name');
        return CloudService::buyApp($name);
    }

    /**
     * 应用插件详情
     * @param Request $request
     * @return \yzh52521\EasyHttp\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    public function detail(Request $request)
    {
        $name = $request->get('name');
        return CloudService::detail($name);
    }

    /**
     * 安装应用
     * @param Request $request
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    public function install(Request $request)
    {
    }

    /**
     * 更新应用
     * @param Request $request
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    public function update(Request $request)
    {
    }

    /**
     * 卸载应用
     * @param Request $request
     * @return void
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    public function uninstall(Request $request)
    {
    }

    /**
     * 处理应用插件状态
     * @param array $data
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    private function getPluginList(array $data)
    {
        // 空数据不处理
        if (empty($data)) {
            return $data;
        }
        // 已安装应用标识
        $pluginNames = SystemPlugin::order('id asc')->column('name');
        // 已安装应用版本
        $pluginVersion = $this->getPluginVersion();
        foreach ($data['data'] as $key => $value) {
            // 是否已购买
            $money = (float) $value['money'];
            if ($money > 0 && !isset($value['order']['order_no'])) {
                $data['data'][$key]['plugin_status'] = 'buy';
            }
            // 是否可安装
            if (isset($value['order']['order_no']) && $value['order']['order_no']) {
                $data['data'][$key]['plugin_status'] = 'install';
            }
            // 是否可卸载
            if (in_array($value['name'], $pluginNames)) {
                $data['data'][$key]['plugin_status'] = 'uninstall';
            }
            // 是否可更新
            $version        = (float) $value['version'];
            $currentVersion = isset($pluginVersion[$value['name']]) ? (float) $pluginVersion[$value['name']] : 0;
            if ($currentVersion > $version) {
                $data['data'][$key]['plugin_status'] = 'update';
            }
        }
        return $data;
    }

    /**
     * 获取版本
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-06
     */
    private function getPluginVersion()
    {
        $list = SystemPlugin::order('id asc')->column('name,version');
        $data = [];
        foreach ($list as $value) {
            $data[$value['name']] = $value['version'];
        }
        return $data;
    }
}