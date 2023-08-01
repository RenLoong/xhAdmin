<?php

namespace app\admin\controller;

use app\common\builder\FormBuilder;
use app\admin\model\Store;
use app\common\manager\StoreAppMgr;
use app\common\service\SystemInfoService;
use app\BaseController;
use support\Request;

/**
 * 租户授权应用
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-09
 */
class StoreAppController extends BaseController
{
    /**
     * 模型
     * @var Store
     */
    protected $model;

    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function __construct()
    {
        $this->model = new Store;
    }

    /**
     * 租户应用授权
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-09
     */
    public function index(Request $request)
    {
        $store_id = (int)$request->get('store_id',0);
        $model = $this->model;
        $where = [
            'id' => $store_id
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('找不到该租户数据');
        }
        if ($request->method() == 'PUT') {
            $plugins_name = $request->post('plugins_name', []);
            $model->plugins_name = $plugins_name;
            if (!$model->save()) {
                return $this->fail('授权失败');
            }
            return $this->success('授权成功');
        }
        $systemInfo = SystemInfoService::info();
        $query = [
            'active' => '2',
            'limit' => 1000,
            'saas_version' => $systemInfo['system_version']
        ];
        $plugins = [];
        try {
            $plugins = StoreAppMgr::getBuyInstallApp();
        } catch (\Throwable $e) {
            p($e->getMessage());
        }
        $builder = new FormBuilder;
        $data = $builder
            ->setMethod('PUT')
            ->addRow('id', 'input', '代理编号', '', [
                'disabled' => true,
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('title', 'input', '代理名称', '', [
                'disabled' => true,
                'col' => [
                    'span' => 12
                ],
            ])
            ->addComponent('plugins_name', 'remote', '授权应用', [], [
                'col' => [
                    'span' => 24
                ],
                'props' => [
                    'file' => 'remote/app/auth',
                    'ajaxParams' => [
                        'plugin_list' => $plugins
                    ],
                ],
            ])
            ->setData($model)
            ->create();
        return parent::successRes($data);
    }
}