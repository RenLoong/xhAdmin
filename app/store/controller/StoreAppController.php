<?php

namespace app\store\controller;

use app\common\builder\FormBuilder;
use app\common\enum\StatusEnum;
use app\common\manager\StoreAppMgr;
use app\common\service\SystemInfoService;
use app\BaseController;
use app\common\manager\PluginMgr;
use app\store\validate\StoreApp;
use Exception;
use support\Log;
use support\Request;
use think\facade\Db;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\PluginRequest;

/**
 * 应用管理
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-03
 */
class StoreAppController extends BaseController
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
        $store_id     = hp_admin_id('hp_store');
        $platformType = $request->get('platform', '');
        $model        = $this->model;
        $where        = [
            ['store_id','=', $store_id]
        ];
        if ($platformType) {
            $where[] = ['platform', '=', $platformType];
        }
        $data = $model->where($where)
            ->order(['id' => 'desc'])
            ->select()
            ->each(function ($item) {
                return $item;
            })
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
    public function create(Request $request)
    {
        $platform = $request->get('platform', '');
        if ($request->method() === 'POST') {
            $post                   = $request->post();
            $post['store_id']       = hp_admin_id('hp_store');
            $post['platform']       = $platform;
            $post['status']         = '20';

            hpValidate(StoreApp::class, $post, 'add');

            Db::startTrans();
            try {
                # 创建项目
                $model = $this->model;
                $model->save($post);
                # 创建项目配置
                # 执行项目插件方法
                $class = "\\plugin\\{$model['name']}\\api\\Created";
                if (method_exists($class, 'createAdmin')) {
                    $post['id'] = $model->id;
                    $logicCls   = new $class;
                    $logicCls->createAdmin($post);
                }
                Db::commit();
                return $this->success('操作成功');
            } catch (\Throwable $e) {
                Db::rollback();
                return $this->fail($e->getMessage());
            }
        }
        $platforms = $this->plugins($request);
        $platformList = [];
        foreach ($platforms as $value) {
            $item           = [
                'label'     => $value['title'],
                'value'     => $value['name'],
            ];
            $platformList[] = $item;
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST')
            ->addRow('title', 'input', '项目名称', '', [
                'col' => 12,
            ])
            ->addRow('name', 'select', '所属应用', '', [
                'col' => 12,
                'options' => $platformList
            ])
            ->addComponent('logo', 'uploadify', '项目图标', '', [
                'props' => [
                    'type' => 'image',
                    'format' => ['jpg', 'jpeg', 'png']
                ],
            ]);
        $data = $builder->create();
        return parent::successRes($data);
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
        $app_id = $request->get('id','');
        $model  = $this->model;
        $where  = [
            ['id', '=', $app_id],
        ];
        $model  = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该应用不存在');
        }
        if ($request->method() === 'PUT') {
            $post = $request->post();

            hpValidate(StoreApp::class, $post, 'edit');

            Db::startTrans();
            try {
                $model->save($post);
                // 执行应用插件方法
                $class = "\\plugin\\{$model->name}\\api\\Created";
                if (method_exists($class, 'createAdmin')) {
                    $post['id'] = $model->id;
                    $logicCls   = new $class;
                    $logicCls->createAdmin($post);
                }
                Db::commit();
                return $this->success('操作成功');
            } catch (\Throwable $e) {
                Db::rollback();
                return $this->fail($e->getMessage());
            }
        }
        $formData = $model->toArray();
        // 执行应用插件方法
        $class = "\\plugin\\{$model->name}\\api\\Created";
        if (method_exists($class, 'read')) {
            $post['id'] = $model->id;
            $logicCls   = new $class;
            try {
                $userData = $logicCls->read($model->id);
                isset($userData['username']) && $formData['username'] = $userData['username'];
            } catch (\Throwable $e) {
                Log::error("获取管理员数据出错：{$e->getMessage()}");
            }
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST')
            ->addRow('title', 'input', '项目名称', '', [
                'col' => 6,
            ])
            ->addRow('status', 'radio', '项目状态', '10', [
                'col'       => 6,
                'options'   => StatusEnum::getOptions()
            ])
            ->addComponent('name', 'info', '绑定应用', '', [
                'col' => 6,
            ])
            ->addComponent('platform', 'info', '项目类型', '', [
                'col' => 6,
            ])
            ->addComponent('logo', 'uploadify', '项目图标', '', [
                'props' => [
                    'type' => 'image',
                    'format' => ['jpg', 'jpeg', 'png']
                ],
            ]);
        $builder->setFormData($formData);
        $data = $builder->create();
        return parent::successRes($data);
    }

    /**
     * 删除项目
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function del(Request $request)
    {
        $store_id = hp_admin_id('hp_store');
        $app_id = $request->post('id','');
        StoreAppMgr::del([
            'id'            => $app_id,
            'store_id'      => $store_id
        ]);
        return $this->success('操作成功');
    }

    /**
     * 获取已安装插件列表
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function plugins(Request $request)
    {
        $installed  = PluginMgr::getLocalPlugins();
        $systemInfo = SystemInfoService::info();
        $query      = [
            'active'        => '2',
            'limit'         => 1000,
            'plugins'       => $installed,
            'saas_version'  => $systemInfo['system_version']
        ];
        $req        = new PluginRequest;
        $req->list();
        $req->setQuery($query, null);
        $cloud   = new Cloud($req);
        $plugins = $cloud->send();
        return $plugins->data;
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
        $app_id      = $request->post('app_id');
        $model       = $this->model;
        $where       = [
            'platform_id' => $platform_id,
            'id' => $app_id
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
            'id' => $app_id
        ];
        $model = $model->where($where)->find();
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