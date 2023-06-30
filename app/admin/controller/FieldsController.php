<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\builder\ListBuilder;
use app\admin\logic\ModulesLogic;
use app\admin\validate\Fields;
use app\BaseController;
use app\utils\DbMgr;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use support\Request;

/**
 * @title 数据表字段管理
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class FieldsController extends BaseController
{
    private $tableName;
    private $prefixTableName;

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
        $prefix                = config('database.connections.mysql.prefix');
        $this->prefixTableName = $request->get('TABLE_NAME', '');
        $this->tableName       = str_replace($prefix, '', $this->prefixTableName);
        if (!DbMgr::hasTable($this->tableName)) {
            throw new Exception('该数据表不存在');
        }
        if (in_array($this->tableName, ModulesLogic::dropTables())) {
            throw new Exception('系统表，禁止操作字段');
        }
    }

    /**
     * 字段表格配置
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
            ->addActionOptions('操作', [
                'width' => 180
            ])
            ->pageConfig()
            ->rowConfig([
                'keyField' => 'COLUMN_NAME'
            ])
            ->addTopButton('add', '新建字段', [
                'api' => 'admin/Fields/add',
                'path' => '/Fields/add',
                'queryParams' => [
                    'TABLE_NAME' => $this->tableName
                ]
            ], [], [
                'type' => 'primary',
            ])
            ->addRightButton('edit', '编辑', [
                'api' => 'admin/Fields/edit',
                'path' => '/Fields/edit',
                'queryParams' => [
                    'TABLE_NAME' => $this->tableName
                ]
            ], [], [
                'type' => 'primary',
                'link' => true
            ])
            ->addRightButton('del', '删除', [
                'type' => 'confirm',
                'api' => 'admin/Fields/del',
                'method' => 'delete',
            ], [
                'title' => '温馨提示',
                'content' => '是否确认删除该数据，该操作将不可恢复',
            ], [
                'type' => 'error',
                'link' => true
            ])
            ->addColumn('COLUMN_NAME', '字段')
            ->addColumn('DATA_TYPE', '字段类型', [
                'width' => 130
            ])
            ->addColumn('COLUMN_COMMENT', '备注')
            ->addColumn('CHARACTER_MAXIMUM_LENGTH', '长度', [
                'width' => 130
            ])
            ->addColumn('COLUMN_DEFAULT', '默认值', [
                'width' => 180
            ])
            ->create();
        return $this->successRes($data);
    }

    /**
     * 字段列表数据
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
        $list = DbMgr::instance()->select("SELECT * FROM information_schema.COLUMNS where TABLE_SCHEMA = '$database' and table_name = '$table' order by ORDINAL_POSITION limit $offset,$limit");
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
     * 新建字段
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function add(Request $request)
    {
        if ($request->method() === 'POST') {
            # 获取数据
            $post = $request->post();
            # 数据验证
            hpValidate(Fields::class, $post);
            # 验证字段是否存在
            if (DbMgr::schema()->hasColumn($this->tableName, $post['name'])) {
                return $this->fail('字段已存在');
            }
            # 创建字段
            DbMgr::schema()
                ->table($this->tableName, function (Blueprint $table) use ($post) {
                    $params = [];
                    # 判断是否有长度
                    if (in_array($post['type'], ['string', 'char']) || stripos($post['type'], 'time') !== false) {
                        $params['length'] = $post['length'];
                    }
                    # 检测是否枚举类型
                    if ($post['type'] === 'enum') {
                        $params['length'] = array_map('trim', explode(',', $post['length']));
                    }
                    # 检测是否浮点类型
                    if (in_array($post['type'], ['float', 'decimal', 'double'])) {
                        $params['length'] = array_map('trim', explode(',', $post['length']));
                    }
                    # 设置字段基础参数
                    $table->addColumn($post['type'], $post['name'], $params);
                    $table->nullable();
                    // $table->renameColumn()
                });
            return $this->success('创建成功');
        }
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('POST')
            ->addRow('name', 'input', '字段名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('comment', 'input', '字段注释', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('type', 'select', '字段类型', '', [
                'col' => [
                    'span' => 12
                ],
                'options' => ModulesLogic::getFieldTypeSelect()
            ])
            ->addRow('extra', 'textarea', '扩展数据', '', [
                'col' => [
                    'span' => 12
                ],
                'placeholder' => '可选填，扩展数据'
            ])
            ->addRow('length', 'input', '数据长度', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('default', 'input', '默认数据', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->create();
        return $this->successRes($data);
    }

    /**
     * 修改字段
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function edit(Request $request)
    {
        $data = [];
        return $this->successRes($data);
    }

    /**
     * 删除字段
     * @param \support\Request $request
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function del(Request $request)
    {
    }
}