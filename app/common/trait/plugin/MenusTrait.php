<?php
namespace app\common\trait\plugin;

use app\admin\validate\SystemAuthRule;
use app\common\builder\FormBuilder;
use app\common\builder\ListBuilder;
use app\common\enum\AuthRuleRuleType;
use app\common\enum\AuthRuleRuleTypeStyle;
use app\common\enum\ShowStatus;
use app\common\enum\ShowStatusStyle;
use app\common\enum\YesNoEum;
use app\common\enum\YesNoEumStyle;
use app\common\manager\PluginMgr;
use app\common\utils\Data;
use app\common\utils\Json;
use FormBuilder\Factory\Elm;
use app\common\enum\AuthRuleMethods;
use support\Request;

/**
 * 权限菜单
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
trait MenusTrait
{
    // 使用JSON工具类
    use Json;

    /**
     * 应用ID（null则获取系统配置）
     * @var int|null
     */
    protected $saas_appid = null;

    /**
     * 插件路径前缀
     * @var string|null
     */
    protected $pluginPrefix = null;

    /**
     * 获取菜单表格
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data    = $builder
            ->addActionOptions('操作', [
                'width' => 180
            ])
            ->treeConfig([
                'rowField' => 'id',
            ])
            ->addTopButton('add', '添加', [
                'api' => $this->pluginPrefix . '/admin/Menus/add',
                'path' => '/Menus/add'
            ], [], [
                'type' => 'primary'
            ])
            ->addTopButton('add', '添加资源菜单', [
                'api' => $this->pluginPrefix . '/admin/Menus/addResource',
                'path' => '/Menus/addResource'
            ], [], [
                'type' => 'primary'
            ])
            ->addRightButton('edit', '修改', [
                'api' => $this->pluginPrefix . '/admin/Menus/edit',
                'path' => '/Menus/edit'
            ], [], [
                'type' => 'primary',
            ])
            ->addRightButton('del', '删除', [
                'type' => 'confirm',
                'api' => $this->pluginPrefix . '/admin/Menus/del',
                'method' => 'delete',
            ], [
                'type' => 'error',
                'title' => '温馨提示',
                'content' => '是否确认删除该数据',
            ], [
                'type' => 'danger',
            ])
            ->addColumn('path', '权限地址', [
                'treeNode' => true
            ])
            ->addColumn('title', '权限名称')
            ->addColumnEle('show', '是否显示', [
                'width' => 100,
                'params' => [
                    'type' => 'tags',
                    'options' => ShowStatus::dictOptions(),
                    'style' => ShowStatusStyle::parseAlias('type'),
                ],
            ])
            ->addColumnEle('is_api', '是否接口', [
                'width' => 100,
                'params' => [
                    'type' => 'tags',
                    'options' => YesNoEum::dictOptions(),
                    'style' => YesNoEumStyle::parseAlias('type'),
                ],
            ])
            ->addColumnEle('component', '组件类型', [
                'width' => 120,
                'params' => [
                    'type' => 'tags',
                    'options' => AuthRuleRuleType::dictOptions(),
                    'style' => AuthRuleRuleTypeStyle::parseAlias('type', false),
                ],
            ])
            ->addColumn('method', '请求类型', [
                'width' => 180
            ])
            ->create();
        return $this->successRes($data);
    }

    /**
     * 获取列表
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function index(Request $request)
    {
        $data = PluginMgr::getMenuList($request->plugin);
        return $this->successRes($data);
    }

    /**
     * 添加菜单
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $post  = $request->post();
            $pluginMenusPath = root_path() . "plugin/{$request->plugin}/menus.json";
            if (!file_exists($pluginMenusPath)) {
                return $this->fail('插件菜单文件不存在');
            }
            // 数据验证
            hpValidate(SystemAuthRule::class, $post, 'add');
            // 额外验证
            if ($post['component'] !== 'remote/index') {
                if (!isset($post['path']) || !$post['path'] && $post['is_api'] === '20') {
                    return $this->fail('请输入权限路由');
                }
                if (!isset($post['method']) || empty($post['method'])) {
                    return $this->fail('至少选择一个请求类型');
                }
            }
            if (!isset($post['method'])) {
                $post['method'] = ['GET'];
            }
            $menuData = $post;
            $menuData['pid'] = end($menuData['pid']);
            $data = PluginMgr::getMenuList($request->plugin);
            $data = list_sort_by($data,'id','asc');
            $menuEnd         = end($data);
            $menuId = $menuEnd['id'] + 1;
            $menuData['icon']       = isset($menuData['icon']['icon']) ? $menuData['icon']['icon'] : '';
            $menuData['id']         = $menuId;
            $menuData['is_default'] = '10';
            array_push($data,$menuData);
            $this->saveData($data);
            return $this->success('添加成功');
        }
        $view = $this->formView()->setMethod('POST')->create();
        return $this->successRes($view);
    }

    /**
     * 修改菜单
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function edit(Request $request)
    {
        $id = $request->get('id','');
        if (empty($id)) {
            return $this->fail('参数错误');
        }
        $data = PluginMgr::getMenuList($request->plugin);
        $arrayIndex = array_search($id, array_column($data, 'id'));
        $detail = isset($data[$arrayIndex]) ? $data[$arrayIndex] : [];
        if (empty($detail)) {
            return $this->fail('数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post  = $request->post();
            // 数据验证
            hpValidate(SystemAuthRule::class, $post, 'edit');
            // 额外验证
            if ($post['component'] !== 'remote/index') {
                if (!isset($post['path']) || !$post['path'] && $post['is_api'] === '20') {
                    return $this->fail('请输入权限路由');
                }
                if (!isset($post['method']) || empty($post['method'])) {
                    return $this->fail('至少选择一个请求类型');
                }
            }
            if (!isset($post['method'])) {
                $post['method'] = ['GET'];
            }
            $menuData = $post;
            $menuData['pid']        = end($menuData['pid']);
            $menuData['icon']       = isset($menuData['icon']['icon']) ? $menuData['icon']['icon'] : '';
            $menuData['id']         = $detail['id'];
            $menuData['is_default'] = $detail['is_default'];
            $data[$arrayIndex] = $menuData;
            $this->saveData($data);
            return $this->success('修改成功');
        }
        if (is_string($detail['icon']) && !empty($detail['icon'])) {
            $detail['icon'] = [
                'icon' => $detail['icon'],
            ];
        }
        $view = $this->formView()->setMethod('PUT')->setFormData($detail)->create();
        return $this->successRes($view);
    }

    /**
     * 删除菜单
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function del(Request $request)
    {
        $id = $request->post('id','');
        if (empty($id)) {
            return $this->fail('参数错误');
        }
        $data = PluginMgr::getMenuList($request->plugin);
        $arrayIndex = array_search($id, array_column($data, 'id'));
        $detail = isset($data[$arrayIndex]) ? $data[$arrayIndex] : [];
        if (empty($detail)) {
            return $this->fail('数据不存在');
        }
        # 删除元数据
        unset($data[$arrayIndex]);
        $this->saveData($data);
        return $this->success('删除成功');
    }

    /**
     * 保存菜单数据
     * @param array $data
     * @return mixed
     * @author John
     */
    private function saveData(array $data)
    {
        $request = request();
        $pluginMenusPath = root_path() . "plugin/{$request->plugin}/menus.json";
        if (!file_exists($pluginMenusPath)) {
            return $this->fail('插件菜单文件不存在');
        }
        $data = Data::channelLevel($data,0,'', 'id','pid');
        $data = $this->checkData($data);
        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($pluginMenusPath, $data);
    }

    /**
     * 递归处理数据
     * @param array $data
     * @return array
     * @author John
     */
    private function checkData(array $data)
    {
        $data = array_values($data);
        foreach ($data as $key => $value) {
            if (isset($value['_level'])) {
                unset($data[$key]['_level']);
            }
            if (isset($value['_html'])) {
                unset($data[$key]['_html']);
            }
            if (!empty($value['children'])) {
                $data[$key]['children'] = $this->checkData($value['children']);
            }
        }
        return $data;
    }

    /**
     * 表单视图
     * @return \app\common\builder\FormBuilder
     * @author YC科技
     */
    private function formView(): FormBuilder
    {
        $builder = new FormBuilder;
        $builder->addRow('title', 'input', '菜单名称', '', [
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('pid', 'cascader', '父级菜单', [], [
            'props' => [
                'props' => [
                    'checkStrictly' => true,
                ],
            ],
            'options' => self::getCascaderOptions(),
            'placeholder' => '如不选择则是顶级菜单',
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('component', 'select', '菜单类型', 'none/index', [
            'options' => AuthRuleRuleType::getOptions(),
            'col' => [
                'span' => 12
            ],
            // 使用联动组件
            'control' => [
                [
                    'value' => 'remote/index',
                    'where' => '==',
                    'rule' => [
                        Elm::input('auth_params', '远程组件')
                            ->props([
                                'placeholder' => '示例：remote/index，则会自动加载 http://www.xxx.com/remote/index.vue 文件作为组件渲染',
                            ])
                            ->col([
                                'span' => 12
                            ]),
                    ],
                ],
                [
                    'value' => ['', 'table/index', 'form/index'],
                    'where' => 'in',
                    'rule' => [
                        Elm::input('auth_params', '附带参数')
                            ->props([
                                'placeholder' => '附带地址栏参数（选填），格式：name=楚羽幽&sex=男'
                            ])
                            ->col([
                                'span' => 12
                            ]),
                    ],
                ],
            ],
        ])
        ->addRow('is_api', 'radio', '是否接口', '10', [
            'options' => YesNoEum::getOptions(),
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('path', 'input', '权限路由', '', [
            'placeholder' => '示例：控制器/方法',
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('show', 'radio', '显示隐藏', '10', [
            'options' => ShowStatus::getOptions(),
            'col' => [
                'span' => 12
            ],
        ])
        ->addRow('method', 'checkbox', '请求类型', ['GET'], [
            'options' => AuthRuleMethods::getOptions(),
            'col' => [
                'span' => 12
            ],
        ])
        ->addComponent('icon', 'icons', '菜单图标', '', [
            'col' => [
                'span' => 12
            ],
        ]);
        return $builder;
    }

    /**
     * 获取层级选项菜单
     * @return array
     * @author John
     */
    private static function getCascaderOptions()
    {
        $request = request();
        $data   = PluginMgr::getMenuList($request->plugin);
        $data   = Data::channelLevel($data, 0, '', 'id', 'pid');
        $data   = self::getChildrenOptions($data);
        $data   = array_merge([
            [
                'label' => '顶级权限菜单',
                'value' => 0
            ]
        ], $data);
        return $data;
    }

    /**
     * 递归拼接cascader组件数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-07
     * @param  array $data
     * @return array
     */
    public static function getChildrenOptions(array $data): array
    {
        $list = [];
        $i = 0;
        foreach ($data as $value) {
            $componentText                  = AuthRuleRuleType::getText($value['component']);
            $title                          = "{$value['title']}-{$componentText}";
            $list[$i]['label']              = $title;
            $list[$i]['value']              = $value['id'];
            $list[$i]['disabled']           = false;
            if (!empty($value['disabled'])) {
                $list[$i]['disabled']       = true;
            }
            if ($value['children']) {
                $list[$i]['children']       = self::getChildrenOptions($value['children']);
            }
            $i++;
        }
        return $list;
    }
}