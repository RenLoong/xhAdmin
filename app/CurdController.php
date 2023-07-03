<?php
namespace app;

use app\admin\builder\ListBuilder;
use app\BaseController;
use app\model\Curd;
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
     * 完整表名
     * @var string
     */
    protected $prefixTableName = null;

    /**
     * 
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function __construct()
    {
        $this->modelName = $this->model->getName();
        $this->prefixTableName = $this->model->getTable();
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
        $tableData = $this->getTableData();
        $builder   = new ListBuilder;
        # 设置规则
        foreach ($tableData as $item) {
            $extra = $this->getExtra($item);
            if ($item['list_type'] === 'text') {
                $builder->addColumn($item['field_name'],$item['field_comment'],$extra);
            }else{
                $extra['params']['type'] = $item['list_type'];
                $builder->addColumnEle($item['field_name'], $item['field_comment'], $extra);
            }
        }
        # 生成表格
        $data = $builder->create();
        # 返回数据
        return $this->successRes($data);
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
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', 20);
        $fields  = [];
        $where   = [];
        $orderBy = '';
        $model   = $this->model;
        # 获取列表
        $data = $model
        ->where($where)
        ->order($orderBy)
        ->withoutField($fields)
        ->paginate([
            'list_rows'     => $limit,
            'page'          => $page
        ])
        ->toArray();
        # 返回数据
        return $this->successRes($data);
    }

    /**
     * 获取扩展参数
     * @param array $item
     * @return array<string>
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getExtra(array $item)
    {
        if (empty($item['list_extra'])) {
            return [];
        }
        $extras = explode(',', $item['list_extra']);
        $data = [];
        foreach ($extras as $value) {
            $expl = explode(':', $value);
            if (empty($expl[0]) || empty($expl[1])) {
                continue;
            }
            $data[$expl[0]] = $expl[1];
        }
        return $data;
    }

    /**
     * 获取CURD列表数据
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getTableData()
    {
        $where = [
            ['table_name','=',$this->modelName],
            ['list_type','<>',''],
        ];
        $fields = [
            'field_name',
            'field_name',
            'field_comment',
            'list_type',
            'list_extra',
        ];
        $data = Curd::where($where)
        ->field($fields)
        ->select()
        ->toArray();
        return $data;
    }

    /**
     * 添加数据
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function add(Request $request)
    {
        $data = [];
        return $this->successRes($data);
    }

    /**
     * 修改数据
     * @param \support\Request $request
     * @return mixed
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
     * 删除数据
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function del(Request $request)
    {
        $data = [];
        return $this->successRes($data);
    }
}