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
            ['store_id', '=', $model['store_id']],
            ['saas_appid', '=', $model['id']],
        ];
        $config  = SystemConfig::where($where)->column('value', 'name');
        if (empty($config)) {
            throw new Exception('请先配置系统');
        }
        if (empty($config['web_name'])) {
            throw new Exception('请填写网站名称');
        }
        if (empty($config['web_url'])) {
            throw new Exception('请填写网站域名');
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
        $siteRoot = "{$config['web_url']}/app/{$model['name']}/api/";
        $query    = [
            'appid' => $config['applet_appid'],
            'name' => $model['name'],
            'preview_desc' => '',
            'type' => 'wxmp',
            'siteinfo' => [
                'name' => $config['web_name'],
                'siteroot' => $siteRoot,
                'app_id' => $model['id'],
                'wx_appid' => $config['applet_appid'],
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
        # 数据处理
        foreach ($post as $field => $value) {
            # 图片处理
            if (in_array($field, ['uploadify'])) {
                $post[$field] = empty($value) ? '' : UploadService::path($value);
            }
        }
        # 数据保存更新
        $fields     = $this->configName;
        $where      = [
            ['name', 'in', $fields],
            ['store_id', '=', $model['store_id']],
            ['saas_appid', '=', $model['id']],
        ];
        $confModels = SystemConfig::where($where)->select();
        $isEmpty    = $confModels->toArray();
        if (empty($isEmpty)) {
            throw new Exception('小程序配置项不存在');
        }
        foreach ($confModels as $confModel) {
            if (empty($post[$confModel->name])) {
                throw new Exception("参数错误--[{$confModel->name}]");
            }
            $value            = $post[$confModel->name];
            $confModel->value = $value;
            $confModel->save();
        }
        # 服务端小程序配置
        try {
            if (!empty($post['applet_appid']) && !empty($post['applet_secret']) && !empty($post['applet_privatekey'])) {
                $data = [
                    'appid' => $post['applet_appid'],
                    'secret' => $post['applet_secret'],
                    'privatekey' => $post['applet_privatekey'],
                    'type' => 'wxmp'
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
        $names = [
            'applet_appid',
            'applet_secret',
            'applet_privatekey',
            'applet_state'
        ];
        $where = [
            ['store_id', '=', $model['store_id']],
            ['saas_appid', '=', $model['id']],
            ['name', 'in', $names]
        ];
        $data  = SystemConfig::where($where)->column('value', 'name');
        if (empty($data)) {
            throw new RedirectException('小程序配置不存在', '/#/Index/index');
        }
        return $data;
    }
}