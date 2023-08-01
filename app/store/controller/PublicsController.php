<?php

namespace app\store\controller;

use app\common\service\SystemInfoService;
use app\common\model\Store;
use app\store\model\StoreMenus;
use app\common\utils\Password;
use Exception;
use support\Request;
use app\admin\validate\SystemAdmin as ValidateSystemAdmin;
use app\BaseController;

class PublicsController extends BaseController
{
    /**
     * 不需要登录的方法
     * @var string[]
     */
    protected $noNeedLogin = ['site', 'login', 'captcha'];

    /**
     * 应用信息
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function site()
    {
        $web_logo = getHpConfig('web_logo');
        $systemInfo = SystemInfoService::info();
        $empower_token = empowerFile('token');
        $private_key = empowerFile('private_key');
        $data = [
            'web_name' => getHpConfig('web_name'),
            'web_title' => '登录',
            'web_logo' => empty($web_logo) ? '' : $web_logo,
            'version_name' => $systemInfo['system_version_name'],
            'version' => $systemInfo['system_version'],
            // 版权token
            'empower_token' => $empower_token,
            // 版权私钥
            'empower_private_key' => $private_key,
            // 登录页链接
            'login_link' => [
                'register' => '',
                'forget' => '',
                'other_login' => [],
            ],
            // 公用接口
            'public_api' => [
                'login' => "{$this->moduleName}/Publics/login",
                'loginout' => "{$this->moduleName}/Publics/loginout",
                'user' => "{$this->moduleName}/Publics/user",
                'menus' => "{$this->moduleName}/Publics/menus",
                'clear' => "{$this->moduleName}/Index/clear",
                'lock' => "{$this->moduleName}/Index/lock",
                "user_edit" => "{$this->moduleName}/Store/edit",
            ],
            # 自定义登录页（配合remote_url使用）
            'login_url' => '/store/login',
            // 远程组件
            'remote_url' => [
                [
                    'title' => '用户登录',
                    'path' => '/store/login',
                    'remote' => 'remote/store/login'
                ],
            ],
            // 附件库API
            'uploadify_api' => [
                'index' => "{$this->moduleName}/SystemUpload/index",
                'upload' => "{$this->moduleName}/SystemUpload/upload",
                'edit' => "{$this->moduleName}/SystemUpload/edit",
                'del' => "{$this->moduleName}/SystemUpload/del",
                'move' => "{$this->moduleName}/SystemUpload/move",
            ],
            // 附件库分类
            'uploadify_cate' => [
                'index' => "{$this->moduleName}/SystemUploadCate/index",
                'add' => "{$this->moduleName}/SystemUploadCate/add",
                'edit' => "{$this->moduleName}/SystemUploadCate/edit",
                'del' => "{$this->moduleName}/SystemUploadCate/del",
            ],
            // 主题配置（待扩展）
            'theme'             => [
                'layout'        => 'top',
            ],
        ];
        return parent::successRes($data);
    }

    /**
     * 系统登录
     * @param Request $request
     * @throws Exception
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function login(Request $request)
    {
        // 获取数据
        $post = $request->post();
        // 数据验证
        hpValidate(ValidateSystemAdmin::class, $post, 'login');

        // 查询数据
        $where = [
            'username' => $post['username']
        ];
        $adminModel = Store::where($where)->find();
        if (!$adminModel) {
            return $this->fail('登录账号错误');
        }
        // 验证登录密码
        if (!Password::passwordVerify((string) $post['password'], (string) $adminModel->password)) {
            return $this->fail('登录密码错误');
        }
        // 判断状态
        if ($adminModel->status === '10') {
            return $this->fail('该用户已被冻结');
        }
        // 判断是否受限过期租户
        if (time() > strtotime($adminModel['expire_time'])) {
            return $this->fail('该用户使用权益已过期');
        }
        $session = $request->session();
        $session->set('hp_store', $adminModel->toArray());

        // 更新登录信息
        $ip = $request->getRealIp($safe_mode = true);
        $adminModel->last_login_ip = $ip;
        $adminModel->last_login_time = date('Y-m-d H:i:s');
        $adminModel->save();

        // 返回数据
        return $this->successFul('登录成功', ['token' => $request->sessionId()]);
    }


    /**
     * 获取管理员数据
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function user(Request $request)
    {
        $admin_id = hp_admin_id('hp_store');
        if (!$admin_id) {
            return $this->failFul('登录超时，请重新登录',12000);
        }
        $where = [
            'id' => $admin_id
        ];
        $model = Store::where($where)->find();
        if (!$model) {
            return $this->failFul('该用户不存在',12000);
        }
        # 重新缓存数据
        $storeData = $model->toArray();
        $session = $request->session();
        $session->set('hp_store', $storeData);
        # 前端数据
        $expireDate = date('Y-m-d',strtotime($storeData['expire_time']));
        $data = [
            'id'                => $storeData['id'],
            'nickname'          => $storeData['title'],
            'headimg'           => $storeData['logo'],
            'plugins'           => $storeData['plugins_name'],
            'role'              => [
                'title'         => "权益：{$expireDate}"
            ],
            'menus'             => $this->getMenus()
        ];
        return parent::successRes($data);
    }

    /**
     * 获取菜单数据
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getMenus()
    {
        $order = [
            'sort' => 'asc',
            'id' => 'asc'
        ];
        $field = [
            'id',
            'module',
            'pid',
            'path',
            'title',
            'method',
            'component',
            'auth_params',
            'icon',
            'show',
        ];
        $list = StoreMenus::order($order)
        ->field($field)
        ->select()
        ->each(function ($e) {
            $e->show = $e->show === '20' ? '1' : '0';
            if ($e->component === 'none/index') {
                $e->component = '';
            }
            is_array($e->method) && $e->method = current($e->method);
            return $e;
        })
        ->toArray();
        $data = [
            'active' => '/Index/index',
            'list' => $list
        ];
        return $data;
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function loginout(Request $request)
    {
        $session = $request->session();
        $session->delete('hp_store');
        return parent::success('成功退出');
    }
}