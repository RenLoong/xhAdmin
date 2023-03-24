<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\model\SystemConfig;
use app\admin\model\SystemConfigGroup;
use app\admin\validate\SystemConfig as ValidateSystemConfig;
use app\BaseController;
use app\enum\ConfigGroupCol;
use app\enum\FormType;
use app\service\Upload;
use FormBuilder\Factory\Elm;
use support\Request;

/**
 * 配置项
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-07
 */
class SystemConfigController extends BaseController
{
    // 组件类型
    private $componentType = ['checkbox', 'radio', 'select'];

    /**
     * 表格列
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  Request $request
     * @return void
     */
    public function indexGetTable(Request $request)
    {
        $cid = $request->get('cid');
        $builder = new ListBuilder;
        $data = $builder
            ->addActionOptions('操作', [
                'width'         => 130
            ])
            ->pageConfig()
            ->addTopButton('add', '添加', [
                'api'           => "/admin/SystemConfig/add",
                'queryParams'   => [
                    'cid'       => $cid
                ],
            ], [], [
                'type'          => 'success',
            ])
            ->addRightButton('edit', '修改', [
                'api'           => '/admin/SystemConfig/edit',
                'queryParams'   => [
                    'cid'       => $cid
                ],
            ], [], [
                'type'          => 'primary',
                'link'          => true
            ])
            ->addRightButton('del', '删除', [
                'type'          => 'confirm',
                'api'           => '/admin/SystemConfig/del',
                'method'        => 'delete',
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认删除该数据',
            ], [
                'type'          => 'danger',
                'link'          => true
            ])
            ->addColumn('title', '配置名称')
            ->addColumn('name', '配置标识')
            ->addColumn('type', '表单类型')
            ->addColumn('extra', '扩展数据')
            ->addColumn('placeholder', '配置描述')
            ->create();
        return parent::successRes($data);
    }

    /**
     * 配置列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $cid = $request->get('cid');
        $where = [
            ['cid', '=', $cid],
        ];
        $data = SystemConfig::where($where)->paginate()->toArray();
        return parent::successRes($data);
    }

    /**
     * 系统配置
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-03
     * @return void
     */
    public function form(Request $request)
    {
        if ($request->method() == 'PUT') {
            $post = request()->post();
            foreach ($post as $name => $value) {
                $where = [
                    'name'      => $name
                ];
                $model = SystemConfig::where($where)->find();
                if (!$model) {
                    return parent::fail('数据错误');
                }
                // 更新数据
                $model->value = $value;
                if (is_array($value) && $model['component'] == 'uploadify') {
                    $files = [];
                    foreach ($value as $k => $v) {
                        $files[$k] = Upload::path($v);
                    }
                    $uploadPath = implode(',', $files);
                    $model->value = $uploadPath;
                }
                if ($model->save() === false) {
                    return parent::fail('保存失败');
                }
            }
            return parent::success('保存成功');
        }
        $dataTabs = $this->getTabs();
        $builder = new FormBuilder;
        $builder = $builder->initTabs($dataTabs['active'], [
            'props'             => [
                // 选项卡样式
                'type'          => 'border-card'
            ],
        ]);
        $builder = $builder->setMethod('PUT');
        foreach ($dataTabs['tabs'] as $value) {
            $builder = $builder->addTab(
                $value['name'],
                $value['title'],
                $value['children']
            );
        }
        $data = $builder->endTabs()->create();
        return parent::successRes($data);
    }

    /**
     * 添加配置项
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  Request $request
     * @return void
     */
    public function add(Request $request)
    {
        $cid = $request->get('cid');
        if ($request->method() == 'POST') {
            $post = $request->post();
            $post['cid'] = $cid;
            // 数据验证
            hpValidate(ValidateSystemConfig::class, $post, 'add');
            $model = new SystemConfig;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $formType = FormType::getOptions();
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('POST')
            ->addRow('title', 'input', '配置项名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('name', 'input', '配置项标识', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('value', 'input', '默认数据', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('component', 'select', '表单类型', '', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => $formType,
                // 使用联动组件
                'control'               => [
                    [
                        'value'         => 'uploadify',
                        'where'         => '==',
                        'rule'          => [
                            Elm::textarea('extra', '附件扩展')
                                ->props([
                                    'placeholder'   => '',
                                ])
                        ],
                    ],
                    [
                        'value'         => ['radio', 'checkbox', 'select'],
                        'where'         => 'in',
                        'rule'          => [
                            Elm::textarea('extra', '扩展数据', '0,关闭|1,开启')
                                ->props([
                                    'placeholder'   => '数据示例：0,关闭|1,开启'
                                ])
                        ],
                    ],
                ],
            ])
            ->addRow('placeholder', 'input', '配置项描述', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('sort', 'input', '配置项排序', '0', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 修改配置项
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        if ($request->method() == 'PUT') {
            $post = $request->post();
            // 数据验证
            hpValidate(ValidateSystemConfig::class, $post, 'edit');

            if (!SystemConfig::where(['id' => $id])->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $where = [
            ['id', '=', $id],
        ];
        $model = SystemConfig::where($where)->find();
        if (!$model) {
            return parent::fail('获取数据失败');
        }
        $formType = FormType::getOptions();
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '配置项名称', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('name', 'input', '配置项标识', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('value', 'input', '默认数据', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('component', 'select', '表单类型', '', [
                'col'       => [
                    'span'  => 12
                ],
                'options'   => $formType,
                // 使用联动组件
                'control'               => [
                    [
                        'value'         => 'uploadify',
                        'where'         => '==',
                        'rule'          => [
                            Elm::textarea('extra', '附件扩展')
                                ->props([
                                    'placeholder'   => '',
                                ])
                        ],
                    ],
                    [
                        'value'         => ['radio', 'checkbox', 'select'],
                        'where'         => 'in',
                        'rule'          => [
                            Elm::textarea('extra', '扩展数据', '0,关闭|1,开启')
                                ->props([
                                    'placeholder'   => '数据示例：0,关闭|1,开启'
                                ])
                        ],
                    ],
                ],
            ])
            ->addRow('placeholder', 'input', '配置项描述', '', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->addRow('sort', 'input', '配置项排序', '0', [
                'col'       => [
                    'span'  => 12
                ],
            ])
            ->setData($model)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 删除配置项
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  Request $request
     * @return void
     */
    public function del(Request $request)
    {
        $id = $request->get('id');
        if (!SystemConfig::destroy($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除成功');
    }

    /**
     * 获取配置分组
     *
     * @return array
     */
    private function getTabs(): array
    {
        $list = SystemConfigGroup::distinct()->select()->toArray();
        $tabs = [];
        foreach ($list as $key => $value) {
            $tabs[$key]                 = [
                'title'                 => $value['title'],
                'name'                  => $value['name'],
                'icon'                  => $value['icon'],
            ];
            $col = ConfigGroupCol::getText($value['layout_col']);
            $tabs[$key]['children']     = $this->getConfig($value['id'], (int)$col['col']);
        }
        $active                         = 0;
        $data['active']                 = $list[$active]['name'];
        $data['tabs']                   = $tabs;
        return $data;
    }

    /**
     * 获取系统配置
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-06
     * @param  integer $cid
     * @param  integer $col
     * @return array
     */
    private function getConfig(int $cid, int $col): array
    {
        $map['cid'] = $cid;
        $list = SystemConfig::where($map)->select()->toArray();
        $config = [];
        $builder = new FormBuilder;
        foreach ($list as $value) {
            // 设置数据
            $configValue = $value['value'];
            if ($value['value'] == 'checkbox') {
                $configValue = [$value['value']];
            }
            $options = [];
            // 设置扩展数据
            $extra = [
                'info'      => $value['placeholder'],
                'col'       => [
                    'span'  => $col
                ],
            ];
            if ($value['extra'] && in_array($value['component'], $this->componentType)) {
                $extras = explode('|', $value['extra']);
                foreach ($extras as $key2 => $value2) {
                    list($optionValue, $label)  = explode(',', $value2);
                    $options[$key2]['label']    = $label;
                    $options[$key2]['value']    = $optionValue;
                }
                $extra['options'] = $options;
            }
            if ($value['component'] == 'uploadify') {
                // 重设的模型数据
                $configValue = [Upload::model($configValue)];
                $extra['props']['type'] = 'files';
                // 附件库
                $builder->addComponent(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $extra
                );
            } else {
                // 普通组件
                $builder->addRow(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $extra
                );
            }
        }
        $config = $builder->getBuilder()->formRule();
        return $config;
    }
}
