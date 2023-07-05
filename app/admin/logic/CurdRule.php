<?php
namespace app\admin\logic;
use app\admin\model\Curd;
use app\utils\DbMgr;
use Exception;
use think\helper\Str;

class CurdRule
{
    /**
     * 获取表格规则
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getTableRule(string $tableName)
    {
        $where  = [
            ['table_name', '=', $tableName],
            ['list_type', '<>', ''],
        ];
        $data = self::getRuleData($where);
        if (empty($data)) {
            throw new Exception('表格规则数据为空，无法生成');
        }
        $str = "\t";
        foreach ($data as $value) {
            $str .= <<<STR
            [
                \t\t"type"          => "{$value['list_type']}",
                \t\t"field"         => "{$value['field_name']}",
                \t\t"title"         => "{$value['field_comment']}",
                \t\t"extra"         => [],
            \t\t],
            STR;
        }
        $content = <<<STR
        [
            $str
        \t]
        STR;
        return $content;
    }

    /**
     * 获取表单规则
     * @param string $tableName
     * @param string $formType
     * @throws \Exception
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getFormRule(string $tableName,string $formType)
    {
        $where  = [
            ['table_name', '=', $tableName],
            ["form_{$formType}", '=', '20'],
        ];
        $data = self::getRuleData($where);
        $errorType = [
            'add'       => '添加',
            'edit'      => '修改'
        ];
        if (empty($data)) {
            throw new Exception("{$errorType[$formType]}表单规则数据为空，无法生成");
        }
        $str = "\t";
        foreach ($data as $value) {
            $str .= <<<STR
            [
                \t\t"type"          => "{$value['form_type']}",
                \t\t"field"         => "{$value['field_name']}",
                \t\t"title"         => "{$value['field_comment']}",
                \t\t"value"         => "",
                \t\t"extra"         => [],
            \t\t],
            STR;
        }
        $content = <<<STR
        [
            $str
        \t]
        STR;
        return $content;
    }

    /**
     * 获取CURD列表数据
     * @param array $where
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function getRuleData(array $where)
    {
        $fields = [
            'field_name',
            'field_comment',
            'list_type',
            'form_type',
            'form_add',
            'form_edit',
        ];
        $data   = Curd::where($where)
            ->order('list_sort asc')
            ->field($fields)
            ->select()
            ->toArray();
        return $data;
    }

    
    /**
     * 获取菜单生成预览数据
     * @param string $menuPath
     * @param string $database
     * @param string $prefixTableName
     * @param string $app_name
     * @param string $tableName
     * @param int $pid
     * @param string $menu_name
     * @throws \Exception
     * @return array|bool
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getMenusPreView(
        string $menuPath,
        string $database,
        string $prefixTableName,
        string $app_name,
        string $tableName,
        int $pid,
        string $menu_name
    )
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
        # 表名转类名
        $className = Str::studly($tableName);
        # 菜单ID
        $oneMenu        = [];
        $twoMenu        = [];
        # 顶级菜单
        if ($pid === 0) {
            $oneMenu = [
                'id' => $lastMenu['id'] + 1,
                'module' => "app/{$app_name}/admin",
                'path' => "{$className}/group",
                'pid' => $pid,
                'title' => "{$menu_name}",
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
                'path' => "{$className}/tabs",
                'pid' => $oneMenu['id'],
                'title' => "{$menu_name}模块",
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
            'path' => "{$className}/index",
            'pid' => empty($twoMenu['id']) ?  $pid : $twoMenu['id'],
            'title' => "{$menu_name}管理",
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
            'path' => "{$className}/add",
            'pid' => $listMenu['id'],
            'title' => "{$menu_name}-添加",
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
            'path' => "{$className}/edit",
            'pid' => $listMenu['id'],
            'title' => "{$menu_name}-修改",
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
            'path' => "{$className}/del",
            'pid' => $listMenu['id'],
            'title' => "{$menu_name}-删除",
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
            'path' => "{$className}/indexGetTable",
            'pid' => $listMenu['id'],
            'title' => "{$menu_name}-表格",
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
     * 获取CURD代码
     * @param string $app_name
     * @param string $tableName
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getCurdCode(string $app_name, string $tableName)
    {
        # 检测是否选择应用
        if (!$app_name) {
            throw new Exception('请选择需要生成到的应用');
        }
        # 检测应用是否存在
        if (!is_dir(base_path("/plugin/{$app_name}"))) {
            throw new Exception('该应用不存在');
        }
        # 生成类名（转驼峰）
        $className = Str::studly($tableName);
        # 生成到指定路径
        $pluginAdminPath = "/plugin/{$app_name}/app";
        $suffix          = config('app.controller_suffix', '');
        $suffix          = ucfirst($suffix);
        # 生成应用控制器
        $controllerPathRoot = "{$pluginAdminPath}/admin/controller/{$className}{$suffix}.php";
        $controllerPath     = base_path($controllerPathRoot);
        if (!is_dir(dirname($controllerPath))) {
            throw new Exception('应用控制器目录错误');
        }
        # 获取表格规则
        $tableRule          = CurdRule::getTableRule($tableName);
        # 获取表单规则
        $addFormRule        = CurdRule::getFormRule($tableName,'add');
        $editFormRule       = CurdRule::getFormRule($tableName,'edit');
        # CURD模板路径
        $sourcePath         = app_path('/admin/tpl/curd');
        # 控制器模板
        $controllerSource   = file_get_contents("{$sourcePath}/Controller.tpl");
        $str1               = [
            "{PLUGIN_NAME}",
            "{CLASS_NAME}",
            "{SUFFIX}",
            "{TABLE_RULE}",
            "{ADD_FORM_RULE}",
            "{EDIT_FORM_RULE}"
        ];
        $str2             = [
            $app_name,
            $className,
            $suffix,
            $tableRule,
            $addFormRule,
            $editFormRule
        ];
        $controllerSource = str_replace($str1, $str2, $controllerSource);
        # 生成应用模型
        $modelPathRoot = "{$pluginAdminPath}/model/{$className}.php";
        $modelPath     = base_path("{$pluginAdminPath}/model/{$className}.php");
        if (!is_dir(dirname($modelPath))) {
            throw new Exception('应用模型目录错误');
        }
        $modelSource = file_get_contents("{$sourcePath}/Model.tpl");
        $modelSource = str_replace($str1, $str2, $modelSource);
        # 生成应用验证器
        $validatePathRoot = "{$pluginAdminPath}/validate/{$className}.php";
        $validatePath     = base_path("{$pluginAdminPath}/validate/{$className}.php");
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
    public static function getPlugins()
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