<?php

namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\admin\logic\AuthRule;
use app\admin\logic\CurdRule;
use app\admin\logic\ModulesLogic;
use app\admin\model\Curd;
use app\admin\utils\Util;
use app\BaseController;
use app\enum\CurdForm;
use app\enum\CurdTable;
use app\exception\RedirectException;
use app\utils\DataMgr;
use app\utils\DbMgr;
use Exception;
use support\Request;
use think\helper\Str;

/**
 * CRUD一键生成
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class CurdController extends BaseController
{
    # 不带前缀表名
    private $tableName;
    # 带前缀表名
    private $prefixTableName;
    # 数据库名称
    private $database;

    /**
     * 构造函数
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct()
    {
        $request               = request();
        $this->database        = config('database.connections.mysql.database');
        $prefix                = config('database.connections.mysql.prefix');
        $this->prefixTableName = $request->input('TABLE_NAME', '');
        if (strpos($this->prefixTableName, $prefix) === false) {
            $this->tableName       = $this->prefixTableName;
            $this->prefixTableName = "{$prefix}{$this->prefixTableName}";
        } else {
            $this->tableName = str_replace($prefix, '', $this->prefixTableName);
        }
        if (!DbMgr::hasTable($this->tableName)) {
            throw new Exception('该数据表不存在');
        }
        if (in_array($this->tableName, ModulesLogic::dropTables())) {
            throw new RedirectException('系统表，禁止操作字段', '/Modules/index');
        }
    }
    /**
     * CURD列表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data    = $builder
            ->pageConfig()
            ->rowConfig([
                'keyField' => 'field_name'
            ])
            ->addTopButton('add', '构建CURD', [
                'type' => 'remote',
                'modal' => true,
                'api' => 'admin/Curd/add',
                'path' => 'remote/curd',
                'queryParams' => [
                    'TABLE_NAME' => $this->tableName,
                ],
            ], [
                'title' => '构建CURD',
                'style' => [
                    'width' => '60%',
                    'height' => '650px',
                    'showIcon' => false,
                    'maskClosable' => false,
                ],
            ], [
                'type' => 'primary',
            ])
            ->addColumn('field_name', '字段名称')
            ->addColumn('field_comment', '字段备注')
            ->addColumnEle('list_title', '列表名称', [
                'params' => [
                    'type' => 'input',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->tableName}",
                    'props' => [],
                ],
            ])
            ->addColumnEle('list_type', '表格数据', [
                'width' => 180,
                'params' => [
                    'type' => 'select',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->tableName}",
                    'options' => CurdTable::getOptions(),
                ],
            ])
            ->addColumnEle('form_add', '增加表单', [
                'width' => 130,
                'params' => [
                    'type' => 'switch',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->tableName}",
                    'checked' => [
                        'text' => '显示',
                        'value' => '20'
                    ],
                    'unchecked' => [
                        'text' => '不显示',
                        'value' => '10'
                    ]
                ],
            ])
            ->addColumnEle('form_edit', '修改表单', [
                'width' => 130,
                'params' => [
                    'type' => 'switch',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->tableName}",
                    'checked' => [
                        'text' => '显示',
                        'value' => '20'
                    ],
                    'unchecked' => [
                        'text' => '不显示',
                        'value' => '10'
                    ]
                ],
            ])
            ->addColumnEle('is_del', '删除数据', [
                'width' => 130,
                'params' => [
                    'type' => 'switch',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->tableName}",
                    'checked' => [
                        'text' => '支持',
                        'value' => '20'
                    ],
                    'unchecked' => [
                        'text' => '不支持',
                        'value' => '10'
                    ]
                ],
            ])
            ->addColumnEle('form_type', '表单控件', [
                'width' => 150,
                'params' => [
                    'type' => 'select',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->tableName}",
                    'options' => CurdForm::getOptions(),
                    'props' => [],
                ],
            ])
            ->create();
        return $this->successRes($data);
    }

    /**
     * 获取CRUD列表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function index(Request $request)
    {
        # 分页参数
        $page   = (int) $request->get('page', 1);
        $limit  = (int) $request->get('limit', 20);
        $offset = ($page - 1) * $limit;
        # 数据参数
        $database = config('database.connections.mysql.database');
        $table    = $this->prefixTableName;
        # 数据表字段
        $list = DbMgr::instance()->select("SELECT TABLE_NAME as table_name,COLUMN_NAME as field_name,COLUMN_COMMENT as field_comment FROM information_schema.COLUMNS where TABLE_SCHEMA = '{$database}' and table_name = '{$table}' order by ORDINAL_POSITION limit {$offset},{$limit}");
        # 处理字段参数
        $i = 0;
        foreach ($list as $object) {
            if (is_object($object)) {
                $list[$i] = (array) $object;
            }
            $where      = [
                'table_name' => $this->tableName,
                'field_name' => $list[$i]['field_name'],
            ];
            $field      = [
                'table_name',
                'field_name',
                'list_title',
                'list_type',
                'list_extra',
                'form_type',
                'form_add',
                'form_edit',
                'form_extra_add',
                'form_extra_edit',
                'is_del',
            ];
            $fieldModel = Curd::where($where)
                ->field($field)
                ->find();
            $list[$i]   = array_merge($list[$i], [
                'list_type' => '',
                'list_extra' => '',
                'form_type' => 'input',
                'form_add' => '',
                'form_edit' => '',
                'form_extra_add' => '',
                'form_extra_edit' => '',
            ]);
            if ($fieldModel) {
                $list[$i] = array_merge($list[$i], $fieldModel->toArray());
            }
            $i++;
        }
        # 获取总数
        $total = DbMgr::instance()->select("SELECT count(*) as total from information_schema.COLUMNS WHERE table_schema='{$database}' and table_name='{$table}';")[0]->total ?? 0;
        # 返回数据
        $data = [
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'data' => $list
        ];
        return $this->successRes($data);
    }

    /**
     * 生成一键CURD
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function add(Request $request)
    {
        try {
            # 获取参数
            $tableName = ucfirst($this->tableName);
            $app_name  = $request->post('app_name', '');
            $menu_id   = $request->post('menu_id', '');
            if ($app_name === '') {
                return $this->fail('请选择构建至某个应用');
            }
            if ($menu_id === '') {
                return $this->fail('请选择构建至某个菜单');
            }
            # 获取CURD代码及目标路径
            $curdData = CurdRule::getCurdCode($app_name, $tableName);
            if (file_exists(base_path($curdData['controllerPath']))) {
                throw new Exception('该控制器已生成');
            }
            # 构建控制器
            if (!file_put_contents(base_path($curdData['controllerPath']), $curdData['controller'])) {
                throw new Exception('生成控制器失败');
            }
            # 构建模型
            if (file_exists(base_path($curdData['modelPath']))) {
                throw new Exception('该模型已生成');
            }
            if (!file_put_contents(base_path($curdData['modelPath']), $curdData['model'])) {
                throw new Exception('生成模型失败');
            }
            # 构建验证器
            if (file_exists(base_path($curdData['validatePath']))) {
                throw new Exception('该验证器已生成');
            }
            if (!file_put_contents(base_path($curdData['validatePath']), $curdData['validate'])) {
                throw new Exception('生成验证器失败');
            }
            # 是否生成菜单
            if ($menu_id !== 'cancel') {
                $this->createdMenu($app_name, $tableName, (int) $menu_id);
            }
            # 平滑重启服务
            Util::reloadWebman();
            # 返回成功
            return $this->success('CURD操作完成');
        } catch (\Throwable $e) {
            # 回滚文件
            # 返回错误
            return $this->fail($e->getMessage());
        }
    }

    /**
     * 生成菜单
     * @param string $app_name
     * @param string $tableName
     * @param int $pid
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function createdMenu(string $app_name, string $tableName, int $pid)
    {
        $menuPath = base_path("/plugin/{$app_name}/menus.json");
        $menus    = CurdRule::getMenusPreView(
            $menuPath,
            $this->database,
            $this->prefixTableName,
            $app_name,
            $tableName,
            $pid
        );
        $content  = json_encode($menus, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (!file_put_contents($menuPath, $content)) {
            throw new Exception('生成菜单失败');
        }
    }


    /**
     * 编辑CRUD列表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function edit(Request $request)
    {
        if ($request->method() !== 'PUT') {
            return $this->fail('请求方式错误');
        }
        # 获取参数
        $table_name = $this->tableName;
        $field_name = $request->post('field_name', '');
        $field      = $request->post('field', '');
        $value      = $request->post('value', '');
        # 查询参数
        $model = Curd::where([
            'table_name' => $table_name,
            'field_name' => $field_name,
        ])->find();
        if ($model) {
            $model->$field = $value;
        } else {
            $sql       = "SELECT * FROM information_schema.COLUMNS WHERE table_name = ('{$this->prefixTableName}') and COLUMN_NAME = '{$field_name}' ORDER BY ordinal_position";
            $columnObj = DbMgr::instance()->select($sql);
            if (empty($columnObj)) {
                throw new RedirectException('获取字段数据失败', "/Fields/index?TABLE_NAME={$this->prefixTableName}");
            }
            isset($columnObj[0]) && $columnData = (array) $columnObj[0];
            if (empty($columnData)) {
                throw new RedirectException('字段数据出错', "/Fields/index?TABLE_NAME={$this->prefixTableName}");
            }
            $model                = new Curd;
            $model->table_name    = $table_name;
            $model->field_name    = $field_name;
            $model->list_sort     = $columnData['ORDINAL_POSITION'];
            $model->field_comment = $columnData['COLUMN_COMMENT'];
            $model->$field        = $value;
        }
        if (!$model->save()) {
            return $this->fail('修改失败');
        }
        return $this->success('修改完成');
    }

    /**
     * 获取CURD详情
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function detail(Request $request)
    {
        $data = CurdRule::getPlugins();
        return $this->successRes($data);
    }

    /**
     * 获取应用菜单列表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function getPluginMenus(Request $request)
    {
        $app_name  = $request->get('app_name', '');
        $className = Str::studly($this->tableName);
        if (empty($app_name)) {
            return $this->fail('请选择应用');
        }
        if (!is_dir(base_path("/plugin/{$app_name}"))) {
            return $this->fail('该应用不存在');
        }
        if (!file_exists(base_path("/plugin/{$app_name}/config/menu.php"))) {
            return $this->fail('该应用菜单文件不存在');
        }
        $list = config("plugin.{$app_name}.menu", []);
        if (empty($list)) {
            return $this->fail('请先设置应用菜单');
        }
        if (empty($list['list'])) {
            return $this->fail('请先设置应用菜单数据');
        }
        $data = $list['list'];
        foreach ($data as $key => $value) {
            $expl = explode('/', $value['path']);
            if (empty($expl[0])) {
                throw new Exception('数据表格式错误');
            }
            # 禁用自身
            if ($expl[0] === $className) {
                $data[$key]['disabled'] = true;
            }
        }
        $data = DataMgr::channelLevel($data, 0, '', 'id', 'pid');
        $data = AuthRule::getChildrenOptions($data);
        $data = array_merge([
            [
                'label' => '不构建菜单',
                'value' => 'cancel'
            ],
            [
                'label' => '构建顶级菜单',
                'value' => 0
            ],
        ], $data);
        return $this->successRes($data);
    }

    /**
     * 获取CURD代码预览
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function getPreview(Request $request)
    {
        # 获取参数
        $app_name = $request->get('app_name', '');
        $menuId   = $request->get('menu_id', '');
        # 数据验证
        if (empty($app_name)) {
            return $this->fail('请选择应用');
        }
        if ($menuId === '') {
            return $this->fail('请选择构建至某个菜单');
        }
        # 组装参数
        $tableName     = ucfirst($this->tableName);
        $menuPath      = "/plugin/{$app_name}/menus.json";
        $menuPathRoot      = base_path("/plugin/{$app_name}/menus.json");
        $data['menus'] = [
            'path' => '',
            'menus' => ''
        ];
        $data['code']  = CurdRule::getCurdCode($app_name, $tableName);
        # 是否生成菜单
        if ($menuId !== 'cancel') {
            $menuView               = CurdRule::getMenusPreView(
                $menuPathRoot,
                $this->database,
                $this->prefixTableName,
                $app_name,
                $tableName,
                (int) $menuId
            );
            $content                = json_encode($menuView, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $data['menus']['path']  = $menuPath;
            $data['menus']['menus'] = $content;
        }
        return $this->successRes($data);
    }
}