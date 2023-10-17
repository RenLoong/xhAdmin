<?php

namespace app\store\controller;

use app\common\builder\FormBuilder;
use app\common\enum\PlatformTypes;
use app\common\enum\StatusEnum;
use app\common\exception\RedirectException;
use app\common\manager\AppletMgr;
use app\common\manager\StoreAppConfigMgr;
use app\common\manager\StoreAppMgr;
use app\common\model\Store;
use app\common\BaseController;
use app\common\manager\PluginMgr;
use app\store\validate\StoreApp;
use Exception;
use think\facade\Log;
use support\Request;
use think\facade\Db;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\UserRequest;

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
     * @var \app\common\model\StoreApp
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
        $limit = $request->get('limit', 12);
        $store_id     = $request->user['id'];
        $platformType = $request->get('platform', '');
        $model = $this->model;
        $where = [
            ['store_id', '=', $store_id]
        ];
        if ($platformType) {
            $where[] = ['platform', '=', $platformType];
        }
        $web_url = getHpConfig('web_url');
        $data    = $model->where($where)
            ->order(['id' => 'desc'])
            ->paginate($limit)
            ->each(function ($item) use ($web_url) {
                # 是否有配置文件
                $setting     = false;
                $settingPath = base_path("plugin/{$item['name']}/config/settings.php");
                $settings = config("plugin.{$item['name']}.settings");
                if (file_exists($settingPath) && !empty($settings)) {
                    $setting = true;
                }
                $item->isSetting = $setting;
                # 应用类型
                $platform           = PlatformTypes::getValue($item['platform']);
                $icon               = empty($platform['icon']) ? '' : "{$web_url}{$platform['icon']}";
                $item->platformLogo = $icon;

                # 应用类型名称
                $platformTitle       = empty($platform['text']) ? '类型错误' : $platform['text'];
                $item->platformTitle = $platformTitle;

                # 返回数据
                return $item;
            });
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
        # 获取创建平台类型
        $platform = $request->get('platform', '');
        # 获取登录租户信息
        $store     = $request->user;
        # 验证平台数量是否充足
        $storePlatformNum = Store::where('id',$store['id'])->value($platform);
        $storeCreatedPlatform = StoreAppMgr::getStoreCreatedPlatform((int)$store['id'],$platform);
        if ($storeCreatedPlatform >= $storePlatformNum) {
            return $this->failRedirect('该平台可用数量不足','/#/Index/index');
        }
        if ($request->method() === 'POST') {
            # 获取数据
            $post = $request->post();

            # 重组数据
            $post['store_id']   = $store['id'];
            $post['platform']   = $platform;
            $post['status']     = '20';

            # 数据验证
            hpValidate(StoreApp::class, $post, 'add');

            # 创建项目
            StoreAppMgr::created($post);

            # 创建成功
            return $this->success('项目创建成功');
        }
        try {
            $platformList = StoreAppMgr::getAuthAppPlatformOptions($store['id'], $platform);
        } catch (\Throwable $e) {
            return $this->failRedirect($e->getMessage(), '/#/Index/index');
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $builder->addRow('title', 'input', '项目名称', '', [
            'col' => 12,
        ]);
        $builder->addRow('name', 'select', '所属应用', '', [
            'col'           => 12,
            'noDataText'    => '您还没有更多的已授权应用',
            'options'       => $platformList
        ]);
        $builder->addRow('username', 'input', '超管账号', '', [
            'col' => 12,
        ]);
        $builder->addRow('password', 'input', '登录密码', '', [
            'col' => 12,
        ]);
        $builder->addComponent('logo', 'uploadify', '项目图标', '', [
            'col' => 12,
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
        $store_id = $request->user['id'];
        $app_id = $request->get('id', '');
        $model = $this->model;
        $where = [
            ['id', '=', $app_id],
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('该应用不存在');
        }
        # 检测应用对SAAS版本支持
        try {
            if (!PluginMgr::checkPluginSaasVersion($model['name'])) {
                throw new Exception('请先更新应用');
            }
        } catch (\Throwable $e) {
            throw new RedirectException($e->getMessage(), "/#/Index/index");
        }
        if ($request->method() === 'PUT') {
            $post = $request->post();
            $post['store_id'] = $store_id;

            hpValidate(StoreApp::class, $post, 'edit');

            Db::startTrans();
            try {
                # 修改项目
                $model->save($post);
                // 执行应用插件方法
                $class = "\\plugin\\{$model->name}\\api\\Created";
                if (method_exists($class, 'createAdmin')) {
                    $post['id'] = $model->id;
                    $logicCls = new $class;
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
            $logicCls = new $class;
            try {
                $userData = $logicCls->read($model->id);
                isset($userData['username']) && $formData['username'] = $userData['username'];
            } catch (\Throwable $e) {
                Log::error("获取管理员数据出错：{$e->getMessage()}");
            }
        }
        $builder = new FormBuilder;
        $builder->setMethod('PUT')
            ->addRow('title', 'input', '项目名称', '', [
                'col' => 12,
            ])
            ->addRow('status', 'radio', '项目状态', '10', [
                'col' => 6,
                'options' => StatusEnum::getOptions()
            ])
            ->addComponent('platform', 'info', '项目类型', '', [
                'col' => 12,
            ])
            ->addComponent('name', 'info', '绑定应用', '', [
                'col' => 12,
            ])
            ->addRow('username', 'input', '超管账号', '', [
                'col' => 12,
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col' => 12,
            ])
            ->addComponent('logo', 'uploadify', '项目图标', '', [
                'col' => 12,
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
        # 获取数据
        $saas_appid = (int)$request->post('id', 0);
        # 删除项目
        StoreAppMgr::del($saas_appid);
        return $this->success('操作成功');
    }

    /**
     * 登录项目数据管理
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    public function login(Request $request)
    {
        $app_id = $request->post('appid_id', '');
        $model = $this->model;

        $where = [
            'id' => $app_id
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('找不到该应用');
        }
        # 检测应用是否存在
        if (!is_dir(root_path()."plugin/{$model->name}")) {
            return $this->fail('该项目绑定应用已卸载');
        }
        # 检测应用对SAAS版本支持
        if (!PluginMgr::checkPluginSaasVersion($model['name'])) {
            throw new RedirectException('请先更新应用', "/#/Index/index");
        }
        try {
            $class = "\\plugin\\{$model->name}\\api\\Login";
            if (!class_exists($class)) {
                throw new Exception("该应用插件 [{$model['name']}] 未实现登录类");
            }
            if (!method_exists($class, 'login')) {
                throw new Exception("该应用插件 [{$model['name']}] 未实现登录方法");
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