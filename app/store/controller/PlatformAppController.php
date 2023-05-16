<?php

namespace app\store\controller;

use app\admin\logic\PluginLogic;
use app\admin\service\kfcloud\CloudService;
use app\BaseController;
use app\store\validate\StoreApp;
use Exception;
use support\Request;

/**
 * 应用管理
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-03
 */
class PlatformAppController extends BaseController
{
    /**
     * 模型
     * @var \app\admin\model\StorePlatform
     */
    public $model;

    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function __construct()
    {
        $this->model = new \app\store\model\StoreApp;
    }

    /**
     * 列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-01
     */
    public function index(Request $request)
    {
        $model = $this->model;
        $platform_id = $request->get('id');
        $where = [
            ['platform_id','=',$platform_id],
        ];
        $data = $model->where($where)
        ->order(['id'=>'desc'])
        ->select()
        ->toArray();
        return parent::successRes($data);
    }

    /**
     * 添加应用
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public function add(Request $request)
    {
        if ($request->method() === 'POST') {
            $post = $request->post();
            $post['store_id'] = hp_admin_id('hp_store');

            hpValidate(StoreApp::class, $post);

            $model = $this->model;
            if (!$model->save($post)) {
                return $this->fail('操作失败');
            }
            return $this->success('操作成功');
        }
        return $this->fail('请求类型错误');
    }

    /**
     * 修改应用
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    public function edit(Request $request)
    {
        $app_id = $request->get('app_id');
        $model  = $this->model;
        $where  = [
            ['id','=',$app_id],
        ];
        $model  = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该应用不存在');
        }
        if ($request->method() === 'POST') {
            $post = $request->post();

            hpValidate(StoreApp::class, $post);

            if (!$model->save($post)) {
                return $this->fail('操作失败');
            }
            return $this->success('操作成功');
        }
        return $this->successRes($model->toArray());
    }

    /**
     * 获取已安装插件列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-11
     */
    public function plugins(Request $request)
    {
        $installed = PluginLogic::getLocalPlugins();
        $query     = [
            'active'    => '2',
            'limit'     => 1000,
            'plugins'   => $installed
        ];
        $data = CloudService::list($query)->array();
        if (!$data) {
            return $this->successRes([]);
        }
        return $this->successRes($data['data']['data']);
    }

    /**
     * 设置状态
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    public function status(Request $request)
    {
        $platform_id = $request->post('platform_id');
        $app_id = $request->post('app_id');
        $model       = $this->model;
        $where       = [
            'platform_id'   => $platform_id,
            'id'            => $app_id
        ];
        $model       = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该应用不存在');
        }
        $model->status = $model->status === '1' ? '0' : '1';
        if (!$model->save()) {
            return $this->fail('设置状态失败');
        }
        return $this->success('设置状态成功');
    }

    /**
     * 登录应用
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    public function login(Request $request)
    {
        $app_id = $request->post('app_id');
        $model  = $this->model;

        $where = [
            'id'    => $app_id
        ];
        $model  = $model->where($where)->find();
        if (!$model) {
            return $this->fail('找不到该应用');
        }
        try {
            $class = "\\plugin\\{$model->name}\\api\\Login";
            if (!method_exists($class, 'login')) {
                throw new Exception('找不到该应用插件登录方法');
            }
            $logicCls = new $class;
            $response = $logicCls->login($request, (int) $model->id);
            if (!isset($response['url'])) {
                throw new Exception('应用插件没有提供登录地址');
            }
            $data = [
                'url' => $response['url']
            ];
            return $this->successRes($data);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }
}