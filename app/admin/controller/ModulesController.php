<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\logic\ModulesLogic;
use app\admin\validate\Modules;
use app\BaseController;
use app\utils\DbMgr;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use support\Request;

class ModulesController extends BaseController
{
    /**
     * 数据表列表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function indexGetTable(Request $request)
    {
        $builder = new ListBuilder;
        $data    = $builder
            ->addActionOptions('操作', [
                'width' => 300
            ])
            ->pageConfig()
            ->rowConfig([
                'keyField'      => 'TABLE_NAME'
            ])
            ->addTopButton('add', '新建表', [
                'api' => 'admin/Modules/add',
                'path' => '/Modules/add',
            ], [], [
                'type' => 'primary',
            ])
            ->addRightButton('curd', 'CURD', [
                'api' => 'admin/Modules/edit',
                'path' => '/Modules/edit',
            ], [], [
                'type' => 'warning',
                'link' => true
            ])
            ->addRightButton('fields', '字段', [
                'api' => 'admin/Fields/index',
                'path' => '/Fields/index',
            ], [], [
                'type' => 'primary',
                'link' => true
            ])
            ->addRightButton('edit', '编辑', [
                'api' => 'admin/Modules/edit',
                'path' => '/Modules/edit',
            ], [], [
                'type' => 'primary',
                'link' => true
            ])
            ->addRightButton('del', '删除', [
                'type' => 'confirm',
                'api' => 'admin/Modules/del',
                'method' => 'delete',
            ], [
                'title' => '温馨提示',
                'content' => '是否确认删除该数据，该操作将不可恢复',
            ], [
                'type' => 'error',
                'link' => true
            ])
            ->addColumn('CREATE_TIME', '创建时间', [
                'width' => 160
            ])
            ->addColumn('TABLE_NAME', '表名')
            ->addColumn('TABLE_COMMENT', '备注')
            ->addColumn('TABLE_ROWS', '记录数', [
                'width' => 80
            ])
            ->addColumn('ENGINE', '引擎', [
                'width' => 130
            ])
            ->addColumn('TABLE_COLLATION', '字符集', [
                'width' => 180
            ])
            ->create();
        return $this->successRes($data);
    }

    /**
     * 获取表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function index(Request $request)
    {
        # 分页
        $page   = (int) $request->get('page', 1);
        $limit  = (int) $request->get('limit', 20);
        $offset = ($page - 1) * $limit;
        # 字段名
        $field = $request->get('field', 'CREATE_TIME');
        $field = DbMgr::filterAlphaNum($field);
        # 排序
        $order = $request->get('order', 'desc');
        # 库名称
        $database = config('database.connections.mysql.database', '');
        # 表前缀
        $prefix = config('database.connections.mysql.prefix', '');
        # 获取总数
        $total = DbMgr::instance()->select("SELECT count(*)total FROM  information_schema.`TABLES` WHERE  TABLE_SCHEMA='$database'")[0]->total ?? 0;
        # 获取列表
        $tables = DbMgr::instance()->select("SELECT TABLE_NAME,TABLE_COMMENT,ENGINE,TABLE_ROWS,CREATE_TIME,UPDATE_TIME,TABLE_COLLATION FROM  information_schema.`TABLES` WHERE  TABLE_SCHEMA='$database' order by $field $order limit $offset,$limit");
        # 重新获取真实记录数
        if ($tables) {
            $table_names      = array_column($tables, 'TABLE_NAME');
            $table_rows_count = [];
            foreach ($table_names as $prefixTableName) {
                $tableName                          = str_replace($prefix, '', $prefixTableName);
                $table_rows_count[$prefixTableName] = DbMgr::instance()->table($tableName)->count();
            }
            foreach ($tables as $key => $table) {
                $tables[$key]->TABLE_ROWS = $table_rows_count[$table->TABLE_NAME] ?? $table->TABLE_ROWS;
            }
        }
        # 返回数据
        $data = [
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'data' => $tables
        ];
        return $this->successRes($data);
    }

    /**
     * 新建表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function add(Request $request)
    {
        if ($request->method() == 'POST') {
            $data          = $request->post();

            # 数据验证
            hpValidate(Modules::class, $data);

            $table_name    = DbMgr::filterAlphaNum($data['table_name']);
            $table_comment = DbMgr::pdoQuote($data['table_comment']);
            $prefix = config('database.connections.mysql.prefix', '');

            # 检测该表名称
            if (DbMgr::hasTable($table_name)) {
                return $this->fail('该数据表已存在');
            }

            $columns = ModulesLogic::defaultColumns();
            DbMgr::schema()->create($table_name, function (Blueprint $table) use ($columns) {
                $type_method_map = ModulesLogic::methodControlMap();
                foreach ($columns as $column) {
                    if (!isset($column['type'])) {
                        throw new Exception("请为{$column['field']}选择类型");
                    }
                    if (!isset($type_method_map[$column['type']])) {
                        throw new Exception("不支持的类型{$column['type']}");
                    }
                    ModulesLogic::createColumn($column, $table);
                }
                $table->charset   = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                $table->engine    = 'InnoDB';
            });
            # 设置表备注
            $prefixTableName = $prefix . $table_name;
            DbMgr::instance()->statement("ALTER TABLE `{$prefixTableName}` COMMENT {$table_comment}");

            # 创建数据表成功
            return $this->success('创建成功');
        }
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('POST')
            ->addRow('table_name', 'input', '表名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('table_comment', 'input', '表备注', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->create();
        return $this->successRes($data);
    }

    /**
     * 编辑表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function edit(Request $request)
    {
        $prefixTableName = $request->get('TABLE_NAME','');
        $prefix = config('database.connections.mysql.prefix', '');
        $tableName = str_replace($prefix, '', $prefixTableName);
        # 系统表禁止删除
        if (in_array($tableName, ModulesLogic::dropTables())) {
            return $this->fail("{$prefixTableName}属于系统表，禁止编辑");
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();
            if (empty($post['old_table_name'])) {
                return $this->fail('旧表名称数据错误');
            }
            if (!DbMgr::hasTable($post['old_table_name'])) {
                return $this->fail('该数据表不存在');
            }
            # 修改表名称
            if ($post['old_table_name'] !== $post['table_name']) {
                DbMgr::schema()->rename($post['old_table_name'], $post['table_name']);
            }
            # 修改表注释
            if ($post['old_table_comment'] !== $post['table_comment']) {
                $tableName = $prefix . $post['table_name'];
                $table_comment = DbMgr::pdoQuote($post['table_comment']);
                DbMgr::instance()->statement("ALTER TABLE `{$tableName}` COMMENT {$table_comment}");
            }
            return $this->success('表变更成功');
        }
        # 表注释
        $comment = DbMgr::instance()->select("SELECT TABLE_COMMENT FROM  information_schema.`TABLES` WHERE  TABLE_NAME='$prefixTableName'")[0]->TABLE_COMMENT ?? '';
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('PUT')
            ->addRow('old_table_name', 'hidden', '',$tableName)
            ->addRow('old_table_comment', 'hidden', '',$comment)
            ->addRow('table_name', 'input', '表名称', $tableName, [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('table_comment', 'input', '表备注', $comment, [
                'col' => [
                    'span' => 12
                ],
            ])
            ->create();
        return $this->successRes($data);
    }

    /**
     * 删除表
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function del(Request $request)
    {
        $prefixTableName = $request->post('TABLE_NAME');
        $prefix = config('database.connections.mysql.prefix', '');
        $tableName = str_replace($prefix, '', $prefixTableName);
        # 系统表禁止删除
        if (in_array($tableName, ModulesLogic::dropTables())) {
            return $this->fail("{$prefixTableName}属于系统表，禁止删除");
        }
        # 执行删除：控制器，模型，验证器
        # 执行删除数据表
        DbMgr::schema()->drop($tableName);
        # 删除完成
        return $this->success('删除成功');
    }
}