<?php
namespace app\common\manager;

use app\common\exception\RedirectException;
use app\common\model\StoreApp;
use app\common\model\SystemConfig;
use app\common\service\UploadService;
use Exception;
use support\Request;
use YcOpen\CloudService\Request as CloudServiceRequest;

/**
 * 小程序管理类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class AppletMgr
{
    /**
     * 请求对象
     * @var Request
     */
    protected $request = null;

    /**
     * 项目模型
     * @var StoreApp
     */
    protected $model;

    /**
     * 配置名称
     * @var array
     */
    protected $configName = [
        'applet_appid',
        'applet_secret',
        'applet_privatekey',
        'applet_state',
    ];

    /**
     * 构造函数
     * @param \support\Request $request
     * @param \app\common\model\StoreApp $storeApp
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function __construct(Request $request, StoreApp $storeApp)
    {
        $this->request = $request;
        $this->model   = $storeApp;
    }

    /**
     * 小程序发布
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function publish()
    {
        $request   = $this->request;
        $appid = $request->post('applet_appid', '');
        $isPreview = $request->post('preview', '10');
        $token     = $this->getToken();
        $tokenQuery = ['token' => $token];
        try {
            $projectCls = CloudServiceRequest::Miniproject();
            if ($isPreview === '20') {
                # 发布后二维码预览
                $data = $projectCls
                    ->miniprojectPreview()
                    ->setQuery($tokenQuery)
                    ->cloud()
                    ->send();
                $data = $data->toArray();
                $query  = [
                    'appid'     => $appid,
                    'name'      => $this->model['name'],
                ];
                $qrcode = $projectCls
                ->setQuery($query)
                ->miniprojectPreviewQrcode();
                $data =  [
                    'preview' => true,
                    'qrcode' => $qrcode,
                ];
                return JsonMgr::successFul('发布成功', $data);
            } else {
                # 纯发布
                $projectCls
                    ->miniprojectUpload()
                    ->setQuery($tokenQuery)
                    ->cloud()
                    ->send();
                return JsonMgr::successFul('发布成功',[
                    'preview'   => false
                ]);
            }
        } catch (\Throwable $e) {
            return JsonMgr::fail("发布失败：{$e->getMessage()}");
        }
    }

    /**
     * 获取令牌桶
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function getToken()
    {
        $request = $this->request;
        $model   = $this->model;
        $where   = [
            ['name', 'in', $this->configName],
            ['store_id', '=', $model['store_id']],
            ['saas_appid', '=', $model['id']],
        ];
        $config  = SystemConfig::where($where)->column('value', 'name');
        if (empty($config)) {
            throw new Exception('请先配置系统');
        }
        if (empty($config['applet_appid'])) {
            throw new Exception('请填写APPID');
        }
        if (empty($config['applet_secret'])) {
            throw new Exception('请填写secret');
        }
        if (empty($config['applet_privatekey'])) {
            throw new Exception('请填写privatekey');
        }
        if (empty($model['title'])) {
            throw new Exception('请填写项目名称');
        }
        if (empty($model['url'])) {
            throw new Exception('请填写项目域名');
        }
        if (empty($model['name'])) {
            throw new Exception('项目绑定应用错误');
        }
        if (!is_dir(base_path("/plugin/{$model['name']}"))) {
            throw new Exception('项目绑定的应用未安装');
        }
        $query    = [
            'appid'             => $config['applet_appid'],
            'name'              => $model['name'],
            'preview_desc'      => '',
            'type'              => 'wxmp',
            'siteinfo'          => [
                'name'          => $model['title'],
                'siteroot'      => $model['url'],
                'app_id'        => $model['id'],
                'wx_appid'      => $config['applet_appid'],
            ]
        ];
        $data = CloudServiceRequest::Miniproject()
            ->getUploadToken()
            ->setParams($query)
            ->cloud()
            ->send();
        return $data['token'];
    }

    /**
     * 小程序配置
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function config()
    {
        $request = $this->request;
        $model   = $this->model;
        $post    = $request->post();
        foreach ($post as $field => $value) {
            $where      = [
                ['name', '=', $field],
                ['store_id', '=', $model['store_id']],
                ['saas_appid', '=', $model['id']],
            ];
            $confModel = SystemConfig::where($where)->find();
            if (!$confModel) {
                $confModel = new SystemConfig;
                $confModel->name        = $field;
                $confModel->store_id    = $model['store_id'];
                $confModel->saas_appid  = $model['id'];
            }
            $confModel->value = $value;
            $confModel->save();
        }
        # 服务端小程序配置
        try {
            if (!empty($post['applet_appid']) && !empty($post['applet_secret']) && !empty($post['applet_privatekey'])) {
                $data = [
                    'appid'         => $post['applet_appid'],
                    'secret'        => $post['applet_secret'],
                    'privatekey'    => $post['applet_privatekey'],
                    'type'          => 'wxmp'
                ];
                CloudServiceRequest::Miniproject()
                    ->setConfig()
                    ->setParams($data)
                    ->cloud()
                    ->send();
            }
        } catch (\Throwable $e) {
            return JsonMgr::fail($e->getMessage());
        }
        return JsonMgr::success('保存成功');
    }

    /**
     * 小程序详情
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function detail()
    {
        $model          = $this->model;
        $data           = $model->toArray();
        $data['config'] = $this->getConfig();
        return JsonMgr::successRes($data);
    }

    /**
     * 获取小程序配置
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getConfig()
    {
        $model = $this->model;
        $where = [
            ['store_id', '=', $model['store_id']],
            ['saas_appid', '=', $model['id']],
            ['name', 'in', $this->configName]
        ];
        $data  = SystemConfig::where($where)->column('value', 'name');
        if (empty($data)) {
            $data = [
                'applet_appid'          => '',
                'applet_secret'         => '',
                'applet_privatekey'     => '',
                'applet_state'          => '',
            ];
        }
        return $data;
    }
}