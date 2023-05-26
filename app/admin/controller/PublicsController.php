<?php

namespace app\admin\controller;

use app\utils\Password;
use Exception;
use support\Request;
use app\admin\model\SystemAdmin;
use app\admin\utils\VueRoutesMgr;
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
        $empower_token = empowerFile('token');
        $private_key = empowerFile('private_key');
        $moduleName = getModule('admin');
        $data       = [
            'web_name'       => getHpConfig('web_name'),
            'web_title'      => '登录',
            'web_logo'       => getHpConfig('web_logo'),
            // 版权token
            'empower_token' => $empower_token,
            // 版权私钥
            'empower_private_key' => $private_key,
            // 登录页链接
            'login_link'     => [
                'register'    => '',
                'forget'      => '',
                'other_login' => [],
            ],
            // 公用接口
            'public_api'     => [
                // 登录接口
                'login'             => "{$moduleName}/Publics/login",
                // 自定义登录页
                'login_file'        => '',
                // 退出接口
                'loginout'          => "{$moduleName}/Publics/loginout",
                // 获取用户信息
                'user'              => "{$moduleName}/Publics/user",
                // 获取权限菜单
                'menus'             => "{$moduleName}/Publics/menus",
                // 清除缓存
                'clear'             => "{$moduleName}/Index/clear",
                // 锁定页面
                'lock'              => "{$moduleName}/Index/lock",
                // 修改登录者信息
                "user_edit"         => "{$moduleName}/SystemAdmin/editSelf",
                // 头部toolBar远程文件
                "header_right_file" => "remote/header-toolbar",
            ],
            // 远程组件
            'remote_url'     => [
                /* [
                    'title'  => '用户注册',
                    'path'   => '/register',
                    'remote' => 'remote/register'
                ], */
            ],
            // 附件库API
            'uploadify_api'  => [
                'index'  => "{$moduleName}/SystemUpload/index",
                'upload' => "{$moduleName}/SystemUpload/upload",
                'edit'   => "{$moduleName}/SystemUpload/edit",
                'del'    => "{$moduleName}/SystemUpload/del",
                'move'   => "{$moduleName}/SystemUpload/move",
            ],
            // 附件库分类
            'uploadify_cate' => [
                'index' => "{$moduleName}/SystemUploadCate/index",
                'add'   => "{$moduleName}/SystemUploadCate/add",
                'edit'  => "{$moduleName}/SystemUploadCate/edit",
                'del'   => "{$moduleName}/SystemUploadCate/del",
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
        $where      = [
            'username' => $post['username']
        ];
        $adminModel = SystemAdmin::with(['role'])->where($where)->find();
        if (!$adminModel) {
            throw new Exception('登录账号错误');
        }
        // 验证登录密码
        if (!Password::passwordVerify((string) $post['password'], (string) $adminModel->password)) {
            throw new Exception('登录密码错误');
        }
        if ($adminModel->status == 0) {
            throw new Exception('该用户已被冻结');
        }
        $session = $request->session();
        $session->set('hp_admin', $adminModel->toArray());

        // 更新登录信息
        $ip                          = $request->getRealIp($safe_mode = true);
        $adminModel->last_login_ip   = $ip;
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
    public function user()
    {
        $admin_id = hp_admin_id('hp_admin');
        // 查询数据
        $where      = [
            'id' => $admin_id
        ];
        $adminModel = SystemAdmin::with(['role'])->where($where)->find();
        $data       = $adminModel->toArray();
        return parent::successRes($data);
    }

    /**
     * 获取菜单数据
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function menus()
    {
        $admin_id = hp_admin_id('hp_admin');

        $where = [
            'id' => $admin_id
        ];
        $admin = SystemAdmin::where($where)->find();

        $data = VueRoutesMgr::run($admin);
        return parent::successRes($data);
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
        $session->delete('hp_admin');
        return parent::success('成功退出');
    }
}
