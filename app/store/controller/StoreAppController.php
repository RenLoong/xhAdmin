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
use app\BaseController;
use app\common\manager\PluginMgr;
use app\store\validate\StoreApp;
use Exception;
use support\Log;
use support\Request;
use think\facade\Db;

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
        $store_id = hp_admin_id('hp_store');
        $platformType = $request->get('platform', '');
        $model = $this->model;
        $where = [
            ['store_id', '=', $store_id]
        ];
        if ($platformType) {
            $where[] = ['platform', '=', $platformType];
        }
        $web_url = getHpConfig('web_url');
        $data = $model->where($where)
            ->order(['id' => 'desc'])
            ->select()
            ->each(function ($item) use ($web_url) {
                # 是否有配置文件
                $setting = false;
                $settingPath = base_path("plugin/{$item['name']}/config/settings.php");
                $settings = config("plugin.{$item['name']}.settings");
                if (file_exists($settingPath) && !empty($settings)) {
                    $setting = true;
                }
                $item->isSetting = $setting;
                # 应用类型
                $platform = PlatformTypes::getValue($item['platform']);
                $icon = empty($platform['icon']) ? '' : "{$web_url}{$platform['icon']}";
                $item->platformLogo = $icon;
                # 返回数据
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
        # 获取创建平台类型
        $platform = $request->get('platform', '');
        # 获取登录租户信息
        $store = hp_admin('hp_store');
        # 验证平台数量是否充足
        $storePlatformNum = Store::where('id',$store['id'])->value($platform);
        $storeCreatedPlatform = StoreAppMgr::getStoreCreatedPlatform((int)$store['id'],$platform);
        if ($storeCreatedPlatform >= $storePlatformNum) {
            return $this->failRedirect('该平台可用数量不足','/#/Index/index');
        }
        if ($request->method() === 'POST') {
            $post = $request->post();
            $post['store_id'] = hp_admin_id('hp_store');
            $post['platform'] = $platform;
            $post['status'] = '20';

            hpValidate(StoreApp::class, $post, 'add');

            Db::startTrans();
            try {
                # 创建项目
                $model = $this->model;
                $model->save($post);
                # 执行项目插件方法
                $class = "\\plugin\\{$model['name']}\\api\\Created";
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
        try {
            $platformList = StoreAppMgr::getAuthAppOptions($store['id'], $platform);
        } catch (\Throwable $e) {
            return $this->failRedirect($e->getMessage(), '/#/Index/index');
        }
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $builder->addRow('title', 'input', '项目名称', '', [
            'col' => 12,
        ]);
        $builder->addRow('url', 'input', '项目域名', '', [
            'col' => 12,
            'placeholder' => '不带结尾的网址域名，示例：http://www.kfadmin.com',
        ]);
        $builder->addRow('username', 'input', '超管账号', '', [
            'col' => 12,
        ]);
        $builder->addRow('password', 'input', '登录密码', '', [
            'col' => 12,
        ]);
        $builder->addRow('name', 'select', '所属应用', '', [
            'col' => 12,
            'options' => $platformList
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
        $store_id = hp_admin_id('hp_store');
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
                throw new RedirectException('请先更新应用', "/#/Index/index");
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
            ->addRow('url', 'input', '项目域名', '', [
                'col' => 12,
                'placeholder' => '不带结尾的网址域名，示例：http://www.kfadmin.com',
            ])
            ->addComponent('name', 'info', '绑定应用', '', [
                'col' => 12,
            ])
            ->addComponent('platform', 'info', '项目类型', '', [
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
            ])
            ->addRow('status', 'radio', '项目状态', '10', [
                'col' => 6,
                'options' => StatusEnum::getOptions()
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
        $app_id = $request->post('id', '');
        StoreAppMgr::del([
            'id' => $app_id,
            'store_id' => $store_id
        ]);
        return $this->success('操作成功');
    }

    /**
     * 项目配置
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function config(Request $request)
    {
        $app_id = $request->get('id', '');
        $model = $this->model;
        $where = [
            ['id', '=', $app_id],
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            return $this->fail('项目数据错误');
        }
        $pluginPath = base_path("plugin/{$model->name}");
        if (!is_dir($pluginPath)) {
            throw new RedirectException('该项目绑定应用已卸载', "/#/Index/index");
        }
        $settingPath = "{$pluginPath}/config/settings.php";
        if (!file_exists($settingPath)) {
            throw new RedirectException('该应用插件没有系统配置文件', "/#/Index/index");
        }
        # 检测应用对SAAS版本支持
        try {
            if (!PluginMgr::checkPluginSaasVersion($model['name'])) {
                throw new RedirectException('请先更新应用', "/#/Index/index");
            }
        } catch (\Throwable $e) {
            throw new RedirectException($e->getMessage(), "/#/Index/index");
        }
        $systemConfig = new StoreAppConfigMgr($request, $model);
        $methodFun = 'list';
        if ($request->method() === 'PUT') {
            $methodFun = 'saveData';
        }
        return call_user_func([$systemConfig, $methodFun]);
    }

    /**
     * 小程序配置与发布
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function applet(Request $request)
    {
        $app_id = $request->get('id', '');
        $model = $this->model;
        $where = [
            ['id', '=', $app_id],
        ];
        $model = $model->where($where)->find();
        if (!$model) {
            throw new RedirectException('项目数据错误', "/#/Index/index");
        }
        # 检测应用对SAAS版本支持
        try {
            if (!PluginMgr::checkPluginSaasVersion($model['name'])) {
                throw new RedirectException('请先更新应用', "/#/Index/index");
            }
        } catch (\Throwable $e) {
            throw new RedirectException($e->getMessage(), "/#/Index/index");
        }
        $methodFun = 'detail';
        switch ($request->method()) {
            # 小程序发布
            case 'POST':
                $methodFun = 'publish';
                break;
            # 小程序配置
            case 'PUT':
                $methodFun = 'config';
                break;
        }
        $class = new AppletMgr($request, $model);
        if (!method_exists($class, $methodFun)) {
            throw new RedirectException("未实现项目方法---{$methodFun}", "/#/Index/index");
        }
        return call_user_func([$class, $methodFun]);
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
        if (!is_dir(base_path("plugin/{$model->name}"))) {
            return $this->fail('该项目绑定应用已卸载');
        }
        # 检测应用对SAAS版本支持
        if (!PluginMgr::checkPluginSaasVersion($model['name'])) {
            throw new RedirectException('请先更新应用', "/#/Index/index");
        }
        try {
            $class = "\\plugin\\{$model->name}\\api\\Login";
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