<?php
namespace app;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\BaseController;
use app\model\Curd;
use think\helper\Str;
use support\Request;

/**
 * CURD基类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class CurdController extends BaseController
{
    /**
     * 操作模型
     * @var \app\Model
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $model;

    /**
     * 模型名称
     * @var string
     */
    protected $modelName = null;

    /**
     * 获取表名（不包含前缀）
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $tableName = null;

    /**
     * 完整表名
     * @var string
     */
    protected $prefixTableName = null;

    /**
     * 表格规则
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $tableRule = [];

    /**
     * 添加表单规则
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $addForm = [];

    /**
     * 修改表单规则
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $editForm = [];

    /**
     * 构造函数
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function __construct()
    {
        $this->modelName       = $this->model->getName();
        $this->tableName = Str::snake($this->modelName);
        $this->prefixTableName = $this->model->getTable();
        parent::__construct();
    }

    /**
     * 获取表格配置
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function indexGetTable(Request $request)
    {
        # 实例表格
        $builder = new ListBuilder;
        # 设置分页
        $builder->pageConfig();
        # 获取表格规则
        $tableData = $this->tableRule;
        # 是否支持CURD
        $addButton  = $this->isButton('form_add');
        $editButton = $this->isButton('form_edit');
        $delButton = $this->isButton(['is_del' => '20', 'field_name' => 'id']);
        # 添加按钮
        if ($addButton) {
            $builder->addTopButton('add', '添加', [
                'api' => "app/{$request->plugin}/admin/{$this->modelName}/add",
                'path' => "/{$this->modelName}/add",
            ], [], [
                'type' => 'success'
            ]);
        }
        # 显示操作列
        if ($editButton || $delButton) {
            $builder->addActionOptions('操作选项');
        }
        # 修改按钮
        if ($editButton) {
            $builder->addRightButton('edit', '编辑', [
                'api' => "app/{$request->plugin}/admin/{$this->modelName}/edit",
                'path' => "/{$this->modelName}/edit",
            ], [], [
                'type' => 'success',
            ]);
        }
        # 删除按钮
        if ($delButton) {
            $builder->addRightButton('del', '删除', [
                'type' => 'confirm',
                'api' => "app/{$request->plugin}/admin/{$this->modelName}/del",
                'method' => 'delete',
            ], [
                'title' => '温馨提示',
                'content' => '是否确认删除该数据',
            ], [
                'type' => 'error',
            ]);
        }
        # 设置规则
        foreach ($tableData as $item) {
            $extra = is_array($item['extra']) ? $item['extra'] : [];
            if ($item['type'] === 'text') {
                $builder->addColumn($item['field'], $item['title'], $extra);
            } else {
                $extra['params']['type'] = $item['type'];
                $builder->addColumnEle($item['field'], $item['title'], $extra);
            }
        }
        # 生成表格
        $data = $builder->create();
        # 返回数据
        return $this->successRes($data);
    }

    /**
     * 是否支持按钮
     * @param mixed $where
     * @return int
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function isButton(mixed $where)
    {
        if (is_string($where)) {
            $where = [
                $where => '20'
            ];
        }
        return Curd::where($where)->count();
    }

    /**
     * 获取数据列表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function index(Request $request)
    {
        # 获取数据
        $page    = (int) $request->get('page', 1);
        $limit   = (int) $request->get('limit', 20);
        $fields  = [];
        $where   = [];
        $orderBy = '';
        $model   = $this->model;
        # 获取列表
        $data = $model
            ->where($where)
            ->order($orderBy)
            ->paginate([
                'list_rows' => $limit,
                'page' => $page
            ])
            ->toArray();
        # 返回数据
        return $this->successRes($data);
    }

    /**
     * 添加数据
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function add(Request $request)
    {
        if ($request->method() === 'POST') {
            # 获取参数
            $post = $request->post();
            # 数据验证
            $validateClass = "\\plugin\\{$request->plugin}\\app\\admin\\validate\\{$this->modelName}";
            hpValidate($validateClass, $post,'add');
            # 数据操作
            $model = $this->model;
            if (!$model->save($post)) {
                return $this->fail('操作失败');
            }
            return $this->success('操作成功');
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $formRule = $this->addForm;
        foreach ($formRule as $value) {
            $extra = empty($formRule['extra']) ? [] : $formRule['extra'];
            if (!in_array($value['type'],['switch','icons','uploadify','remote'])) {
                $builder->addRow(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    '',
                    $extra
                );
            } else {
                $builder->addComponent(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    '',
                    $extra
                );
            }
        }
        $data    = $builder->create();
        return $this->successRes($data);
    }

    /**
     * 修改数据
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function edit(Request $request)
    {
        $pkField = 'id';
        $keyValue = $request->get($pkField,'');
        $model = $this->model;
        $where = [
            $pkField        => $keyValue
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->failFul('数据错误，请检查', 305);
        }
        if ($request->method() === 'PUT') {
            # 获取参数
            $post = $request->post();
            # 数据验证
            $validateClass = "\\plugin\\{$request->plugin}\\app\\admin\\validate\\{$this->modelName}";
            hpValidate($validateClass, $post,'edit');
            # 数据操作
            if (!$model->save($post)) {
                return $this->fail('操作失败');
            }
            return $this->success('操作成功');
        }
        $builder = new FormBuilder;
        $builder->setMethod('PUT');
        $formRule = $this->editForm;
        foreach ($formRule as $value) {
            $extra = empty($formRule['extra']) ? [] : $formRule['extra'];
            if (!in_array($value['type'],['switch','icons','uploadify','remote'])) {
                $builder->addRow(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    '',
                    $extra
                );
            } else {
                $builder->addComponent(
                    $value['field'],
                    $value['type'],
                    $value['title'],
                    '',
                    $extra
                );
            }
        }
        $builder->setData($model);
        $data    = $builder->create();
        return $this->successRes($data);
    }

    /**
     * 删除数据
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function del(Request $request)
    {
        $model = $this->model;
        $pkField = 'id';
        $fieldValue = $request->post($pkField, '');
        $where    = [
            $pkField        => $fieldValue
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该数据不存在');
        }
        if (!$model->delete()) {
            return $this->fail('删除失败');
        }
        return $this->success('删除成功');
    }
}