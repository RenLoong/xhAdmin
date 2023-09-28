<?php

namespace app\admin\controller;

use app\common\builder\FormBuilder;
use app\common\builder\ListBuilder;
use app\common\enum\AuthRuleRuleTypeStyle;
use app\common\enum\ShowStatus;
use app\common\enum\ShowStatusStyle;
use app\common\enum\YesNoEum;
use app\common\enum\YesNoEumStyle;
use app\common\service\AuthRuleService;
use app\common\model\SystemAuthRule;
use app\admin\validate\SystemAuthRule as ValidateSystemAuthRule;
use app\common\BaseController;
use app\common\enum\AuthRuleMethods;
use app\common\enum\AuthRuleRuleType;
use app\common\service\FrameworkService;
use support\Request;
use FormBuilder\Factory\Elm;

/**
 * 权限菜单
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-07
 */
class SystemAuthRuleController extends BaseController
{
    /**
     * 表格
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data    = $builder
            ->addActionOptions('操作', [
                'width' => 150
            ])
            ->treeConfig([
                'rowField' => 'id',
            ])
            ->addTopButton('add', '添加', [
                'api'  => 'admin/SystemAuthRule/add',
                'path' => '/SystemAuthRule/add'
            ], [], [
                    'type' => 'success'
                ])
            ->addRightButton('edit', '修改', [
                'api'  => 'admin/SystemAuthRule/edit',
                'path' => '/SystemAuthRule/edit'
            ], [], [
                    'type' => 'primary',
                    'link' => true
                ])
            ->addRightButton('del', '删除', [
                'type'   => 'confirm',
                'api'    => 'admin/SystemAuthRule/del',
                'method' => 'delete',
            ], [
                    'title'   => '温馨提示',
                    'content' => '是否确认删除该数据',
                ], [
                    'type' => 'danger',
                    'link' => true
                ])
            ->addColumn('path_text', '权限地址', [
                'treeNode' => true
            ])
            ->addColumn('title', '权限名称')
            ->addColumnEle('show', '是否显示', [
                'width'  => 100,
                'params' => [
                    'type'    => 'tags',
                    'options' => ShowStatus::dictOptions(),
                    'style'   => ShowStatusStyle::parseAlias('type'),
                ],
            ])
            ->addColumnEle('is_api', '是否接口', [
                'width'  => 100,
                'params' => [
                    'type'    => 'tags',
                    'options' => YesNoEum::dictOptions(),
                    'style'   => YesNoEumStyle::parseAlias('type'),
                ],
            ])
            ->addColumnEle('component', '组件类型', [
                'width'  => 120,
                'params' => [
                    'type'    => 'tags',
                    'options' => AuthRuleRuleType::dictOptions(),
                    'style'   => AuthRuleRuleTypeStyle::parseAlias('type',false),
                ],
            ])
            ->addColumn('method', '请求类型', [
                'width' => 180
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
     * @DateTime 2023-04-29
     */
    public function index(Request $request)
    {
        $list = SystemAuthRule::order(['sort' => 'asc', 'id' => 'asc'])
            ->select()
            ->toArray();
        return parent::successRes($list);
    }

    /**
     * 添加
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function add(Request $request)
    {
        if ($request->method() == 'POST') {
            $post  = $request->post();
            $model = new SystemAuthRule;

            // 数据验证
            hpValidate(ValidateSystemAuthRule::class, $post, 'add');

            // 额外验证
            if ($post['component'] !== 'remote/index') {
                // 接口/表单/表格
                if (!isset($post['namespace']) || !$post['namespace']) {
                    return parent::fail('请输入命名空间');
                }
                if (!isset($post['path']) || !$post['path'] && $post['is_api'] === '1') {
                    return parent::fail('请输入权限路由');
                }
                if (!isset($post['method']) || empty($post['method'])) {
                    return parent::fail('至少选择一个请求类型');
                }
            }
            if (!isset($post['method'])) {
                $post['method'] = ['GET'];
            }

            if (!$model->save($post)) {
                return parent::fail('添加失败');
            }
            // 重启框架
            FrameworkService::reloadSubprocess();
            return parent::success('添加成功');
        }
        $builder = new FormBuilder;
        $view    = $builder
            ->setMethod('POST')
            ->addRow('module', 'input', '模块名称', 'admin', [
                'placeholder' => '如app目录下则直接填写模块名，应用插件则填写：服务商/插件名',
                'col'         => [
                    'span' => 12
                ],
            ])
            ->addRow('pid', 'cascader', '父级菜单', [], [
                'props'       => [
                    'props' => [
                        'checkStrictly' => true,
                    ],
                ],
                'options'     => AuthRuleService::getCascaderOptions(),
                'placeholder' => '如不选择则是顶级菜单',
                'col'         => [
                    'span' => 12
                ],
            ])
            ->addRow('title', 'input', '菜单名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('component', 'select', '菜单类型', 'none/index', [
                'options'   => AuthRuleRuleType::getOptions(),
                'col'       => [
                    'span'  => 12
                ],
                // 使用联动组件
                'control'   => [
                    [
                        'value' => 'remote/index',
                        'where' => '==',
                        'rule'  => [
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
                        'value' => ['','table/index','form/index'],
                        'where' => 'in',
                        'rule'  => [
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
                'options'       => YesNoEum::getOptions(),
                'col'           => [
                    'span'      => 12
                ],
            ])
            ->addRow('namespace', 'input', '命名空间', "\\app\\admin\\controller\\", [
                'placeholder'=> '示例：\\app\\admin\\controller\\',
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('path', 'input', '权限路由', '', [
                'placeholder'=>'示例：控制器/方法',
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('show', 'radio', '显示隐藏', '10', [
                'options' => ShowStatus::getOptions(),
                'col'     => [
                    'span' => 12
                ],
            ])
            ->addRow('method', 'checkbox', '请求类型', ['GET'], [
                'options' => AuthRuleMethods::getOptions(),
                'col'     => [
                    'span' => 12
                ],
            ])
            ->addComponent('icon', 'icons', '菜单图标', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('sort', 'input', '菜单排序', '0', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->create();
        return parent::successRes($view);
    }

    /**
     * 修改
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function edit(Request $request)
    {
        $id    = $request->get('id');
        $where = [
            ['id', '=', $id],
        ];
        $model = SystemAuthRule::where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateSystemAuthRule::class, $post, 'edit');

            // 额外验证
            if ($post['component'] !== 'remote/index') {
                // 接口/表单/表格
                if (!isset($post['namespace']) || !$post['namespace']) {
                    return parent::fail('请输入命名空间');
                }
                if (!isset($post['path']) || !$post['path'] && $post['is_api'] === '1') {
                    return parent::fail('请输入权限路由');
                }
                if (!isset($post['method']) || empty($post['method'])) {
                    return parent::fail('至少选择一个请求类型');
                }
            }

            if (!$model->save($post)) {
                return parent::fail('修改失败');
            }
            // 重启框架
            FrameworkService::reloadSubprocess();
            return parent::success('修改成功');
        }
        $model->pid = [$model['pid']];
        $builder = new FormBuilder;
        $view    = $builder
            ->setMethod('PUT')
            ->addRow('module', 'input', '模块名称', 'admin', [
                'placeholder' => '如app目录下则直接填写模块名，应用插件则填写：服务商/插件名',
                'col'         => [
                    'span' => 12
                ],
            ])
            ->addRow('pid', 'cascader', '父级菜单', [], [
                'props'       => [
                    'props' => [
                        'checkStrictly' => true,
                    ],
                ],
                'options'     => AuthRuleService::getCascaderOptions(),
                'placeholder' => '如不选择则是顶级菜单',
                'col'         => [
                    'span' => 12
                ],
            ])
            ->addRow('title', 'input', '菜单名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('component', 'select', '菜单类型', 'none/index', [
                'options'   => AuthRuleRuleType::getOptions(),
                'col'       => [
                    'span'  => 12
                ],
                // 使用联动组件
                'control'   => [
                    [
                        'value' => 'remote/index',
                        'where' => '==',
                        'rule'  => [
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
                        'value' => ['','table/index','form/index'],
                        'where' => 'in',
                        'rule'  => [
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
                'options'       => YesNoEum::getOptions(),
                'col'           => [
                    'span'      => 12
                ],
            ])
            ->addRow('namespace', 'input', '命名空间', "\\app\\admin\\controller\\", [
                'placeholder'=> '示例：\\app\\admin\\controller\\',
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('path', 'input', '权限路由', '', [
                'placeholder'=>'示例：控制器/方法',
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('show', 'radio', '显示隐藏', '10', [
                'options' => ShowStatus::getOptions(),
                'col'     => [
                    'span' => 12
                ],
            ])
            ->addRow('method', 'checkbox', '请求类型', ['GET'], [
                'options' => AuthRuleMethods::getOptions(),
                'col'     => [
                    'span' => 12
                ],
            ])
            ->addComponent('icon', 'icons', '菜单图标', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('sort', 'input', '菜单排序', '0', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->setData($model)
            ->create();
        return parent::successRes($view);
    }

    /**
     * 删除
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function del(Request $request)
    {
        $id = $request->post('id');
        $where = [
            ['id','=',$id]
        ];
        $model = SystemAuthRule::where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($model->is_system === '1') {
            return parent::fail('系统级权限规则，禁止删除');
        }
        if (!$model->delete()) {
            return parent::fail('删除失败');
        }
        // 重启框架
        FrameworkService::reloadSubprocess();
        return parent::success('删除成功');
    }
}