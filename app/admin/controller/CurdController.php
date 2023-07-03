<?php

namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\admin\logic\AuthRule;
use app\admin\logic\ModulesLogic;
use app\admin\logic\PluginLogic;
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
            ->addTopButton('add', 'CURD构建', [
                'type' => 'remote',
                'modal' => true,
                'api' => 'admin/Curd/add',
                'path' => 'remote/curd',
                'queryParams' => [
                    'TABLE_NAME' => $this->tableName,
                ],
            ], [
                'title' => 'CURD构建',
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
            ->addColumnEle('list_type', '列表控件', [
                'width' => 150,
                'params' => [
                    'type' => 'select',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->prefixTableName}",
                    'options' => CurdTable::getOptions(),
                ],
            ])
            ->addColumnEle('list_extra', '列表额外参数', [
                'params' => [
                    'type' => 'input',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->prefixTableName}",
                    'props' => [],
                ],
            ])
            ->addColumnEle('form_add', '增加', [
                'width' => 100,
                'params' => [
                    'type' => 'switch',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->prefixTableName}",
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
            ->addColumnEle('form_edit', '修改', [
                'width' => 100,
                'params' => [
                    'type' => 'switch',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->prefixTableName}",
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
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->prefixTableName}",
                    'options' => CurdForm::getOptions(),
                    'props' => [],
                ],
            ])
            ->addColumnEle('form_extra_add', '增加表单额外参数', [
                'params' => [
                    'type' => 'input',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->prefixTableName}",
                    'props' => [],
                ],
            ])
            ->addColumnEle('form_extra_edit', '修改表单额外参数', [
                'params' => [
                    'type' => 'input',
                    'api' => "/admin/Curd/edit?TABLE_NAME={$this->prefixTableName}",
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
                'list_type',
                'list_extra',
                'form_type',
                'form_add',
                'form_edit',
                'form_extra_add',
                'form_extra_edit',
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
            $curdData = $this->getCurdCode($app_name, $tableName);
            # 构建控制器
            if (!file_put_contents(base_path($curdData['controllerPath']), $curdData['controller'])) {
                throw new Exception('生成控制器失败');
            }
            # 构建模型
            if (!file_put_contents(base_path($curdData['modelPath']), $curdData['model'])) {
                throw new Exception('生成模型失败');
            }
            # 构建验证器
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
        $menus    = $this->getMenusPreView($menuPath, $app_name, $tableName, $pid);
        $content  = json_encode($menus, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (!file_put_contents($menuPath, $content)) {
            throw new Exception('生成菜单失败');
        }
    }

    /**
     * 获取菜单生成预览数据
     * @param string $menuPath
     * @param string $app_name
     * @param string $tableName
     * @param int $pid
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function getMenusPreView(string $menuPath, string $app_name, string $tableName, int $pid)
    {
        if (!file_exists($menuPath)) {
            throw new Exception('应用菜单文件不存在');
        }
        # 获取菜单数据
        $menus = json_decode(file_get_contents($menuPath), true);
        $menus = empty($menus) ? [] : $menus;
        if ($menus) {
            $menus    = list_sort_by($menus, 'id', 'asc');
            $lastMenu = end($menus);
            if (empty($lastMenu['id'])) {
                throw new Exception('菜单最后的ID数据异常');
            }
        }
        # 获取表信息
        $sql       = "SELECT * from information_schema.TABLES WHERE table_schema='{$this->database}' and table_name='{$this->prefixTableName}';";
        $tableInfo = DbMgr::instance()->select($sql);
        $tableInfo = (array) current($tableInfo);
        # 菜单ID
        $oneMenu        = [];
        $twoMenu        = [];
        # 顶级菜单
        if ($pid === 0) {
            $oneMenu = [
                'id' => $lastMenu['id'] + 1,
                'module' => "app/{$app_name}/admin",
                'path' => "{$tableName}/group",
                'pid' => $pid,
                'title' => "{$tableInfo['TABLE_COMMENT']}",
                'method' => ['GET'],
                'component' => '',
                'auth_params' => '',
                'icon' => '',
                'show' => '1',
                'is_api' => '0'
            ];
            $twoMenu  = [
                'id' => $oneMenu['id'] + 1,
                'module' => "app/{$app_name}/admin",
                'path' => "{$tableName}/tabs",
                'pid' => $oneMenu['id'],
                'title' => "{$tableInfo['TABLE_COMMENT']}模块",
                'method' => ['GET'],
                'component' => '',
                'auth_params' => '',
                'icon' => '',
                'show' => '1',
                'is_api' => '0'
            ];
        }
        # 列表自增ID
        $listMenu = [
            'id' => empty($twoMenu['id']) ?  $lastMenu['id'] + 1 : $twoMenu['id'] + 1,
            'module' => "app/{$app_name}/admin",
            'path' => "{$tableName}/index",
            'pid' => empty($twoMenu['id']) ?  $pid : $twoMenu['id'],
            'title' => "{$tableInfo['TABLE_COMMENT']}管理",
            'method' => ['GET'],
            'component' => 'table/index',
            'auth_params' => '',
            'icon' => '',
            'show' => '1',
            'is_api' => '1'
        ];
        # 新增
        $addMenu = [
            'id' => $listMenu['id'] + 1,
            'module' => "app/{$app_name}/admin",
            'path' => "{$tableName}/add",
            'pid' => $listMenu['id'],
            'title' => "{$tableInfo['TABLE_COMMENT']}-添加",
            'method' => ['GET', 'POST'],
            'component' => 'form/index',
            'auth_params' => '',
            'icon' => '',
            'show' => '0',
            'is_api' => '1'
        ];
        # 修改
        $editMenu = [
            'id' => $addMenu['id'] + 1,
            'module' => "app/{$app_name}/admin",
            'path' => "{$tableName}/edit",
            'pid' => $listMenu['id'],
            'title' => "{$tableInfo['TABLE_COMMENT']}-修改",
            'method' => ['GET', 'PUT'],
            'component' => 'form/index',
            'auth_params' => '',
            'icon' => '',
            'show' => '0',
            'is_api' => '1'
        ];
        # 删除
        $delMenu = [
            'id' => $editMenu['id'] + 1,
            'module' => "app/{$app_name}/admin",
            'path' => "{$tableName}/del",
            'pid' => $listMenu['id'],
            'title' => "{$tableInfo['TABLE_COMMENT']}-删除",
            'method' => ['GET', 'DELETE'],
            'component' => '',
            'auth_params' => '',
            'icon' => '',
            'show' => '0',
            'is_api' => '1'
        ];
        # 表格
        $tableMenu = [
            'id' => $delMenu['id'] + 1,
            'module' => "app/{$app_name}/admin",
            'path' => "{$tableName}/indexGetTable",
            'pid' => $listMenu['id'],
            'title' => "{$tableInfo['TABLE_COMMENT']}-表格",
            'method' => ['GET'],
            'component' => 'table/index',
            'auth_params' => '',
            'icon' => '',
            'show' => '0',
            'is_api' => '1'
        ];
        $dataMenus = [
            empty($oneMenu['path']) ? [] : $oneMenu,
            empty($twoMenu['path']) ? [] : $twoMenu,
            $listMenu,
            $addMenu,
            $editMenu,
            $delMenu,
            $tableMenu,
        ];
        $dataMenus = array_filter($dataMenus);
        $menus     = array_column($menus, null, 'path');
        $data      = array_column($dataMenus, null, 'path');
        $menus     = array_merge($menus, $data);
        $menus     = array_values($menus);
        # 重新排序
        $menus = list_sort_by($menus, 'id', 'asc');
        # 返回菜单数据
        return $menus;
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
            $model             = new Curd;
            $model->table_name = $table_name;
            $model->field_name = $field_name;
            $model->$field     = $value;
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
        $data = $this->getPlugins();
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
        $tableName = ucfirst($this->tableName);
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
            if ($expl[0] === $tableName) {
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
        $menuPath      = base_path("/plugin/{$app_name}/menus.json");
        $data['menus'] = '';
        $data['code']  = $this->getCurdCode($app_name, $tableName);
        # 是否生成菜单
        if ($menuId !== 'cancel') {
            $content       = json_encode($this->getMenusPreView($menuPath, $app_name, $tableName, (int) $menuId), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $data['menus'] = $content;
        }
        return $this->successRes($data);
    }

    /**
     * 获取CURD代码
     * @param string $app_name
     * @param string $tableName
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function getCurdCode(string $app_name, string $tableName)
    {
        # 检测是否选择应用
        if (!$app_name) {
            throw new Exception('请选择需要生成到的应用');
        }
        # 检测应用是否存在
        if (!is_dir(base_path("/plugin/{$app_name}"))) {
            throw new Exception('该应用不存在');
        }
        # 生成到指定路径
        $pluginAdminPath = "/plugin/{$app_name}/app/admin";
        $suffix          = config('app.controller_suffix', '');
        $suffix          = ucfirst($suffix);
        # 生成应用控制器
        $controllerPathRoot = "{$pluginAdminPath}/controller/{$tableName}{$suffix}.php";
        $controllerPath     = base_path($controllerPathRoot);
        if (!is_dir(dirname($controllerPath))) {
            throw new Exception('应用控制器目录错误');
        }
        $sourcePath       = app_path('/admin/tpl/curd');
        $controllerSource = file_get_contents("{$sourcePath}/Controller.tpl");
        $str1             = [
            "{PLUGIN_NAME}",
            "{CLASS_NAME}",
            "{SUFFIX}"
        ];
        $str2             = [
            $app_name,
            $tableName,
            $suffix,
        ];
        $controllerSource = str_replace($str1, $str2, $controllerSource);
        # 生成应用模型
        $modelPathRoot = "{$pluginAdminPath}/model/{$tableName}.php";
        $modelPath     = base_path("{$pluginAdminPath}/model/{$tableName}.php");
        if (!is_dir(dirname($modelPath))) {
            throw new Exception('应用模型目录错误');
        }
        $modelSource = file_get_contents("{$sourcePath}/Model.tpl");
        $modelSource = str_replace($str1, $str2, $modelSource);
        # 生成应用验证器
        $validatePathRoot = "{$pluginAdminPath}/validate/{$tableName}.php";
        $validatePath     = base_path("{$pluginAdminPath}/validate/{$tableName}.php");
        if (!is_dir(dirname($validatePath))) {
            throw new Exception('应用验证器目录错误');
        }
        $validateSource = file_get_contents("{$sourcePath}/Validate.tpl");
        $validateSource = str_replace($str1, $str2, $validateSource);
        # 返回代码
        $data = [
            'controllerPath' => $controllerPathRoot,
            'modelPath' => $modelPathRoot,
            'validatePath' => $validatePathRoot,
            'controller' => $controllerSource,
            'model' => $modelSource,
            'validate' => $validateSource,
        ];
        return $data;
    }

    /**
     * 获取本地应用选项列表
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getPlugins()
    {
        $plugins = PluginLogic::getLocalPlugins();
        $plugins = array_keys($plugins);
        $data    = [];
        foreach ($plugins as $key => $value) {
            $data[$key] = [
                'label' => $value,
                'value' => $value
            ];
        }
        return $data;
    }
}