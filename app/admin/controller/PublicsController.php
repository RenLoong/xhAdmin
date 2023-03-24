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
     *
     * @return void
     */
    public function site()
    {
        $data = [
            'web_name'          => getHpConfig('web_name'),
            'web_title'         => '管理员登录',
            'web_logo'          => getHpConfig('web_logo'),
            'public_api'        => [
                'login'         => 'Publics/login',
                'loginout'      => 'Publics/loginout',
                'user'          => 'Publics/user',
                'menus'         => 'Publics/menus',
                'clear'         => 'Index/clear',
                'lock'          => 'Index/lock',
            ],
            'uploadify_api'     => [
                'index'         => 'SystemUpload/index',
                'upload'        => 'SystemUpload/upload',
                'edit'          => 'SystemUpload/edit',
                'del'           => 'SystemUpload/del',
            ],
            'uploadify_cate'    => [
                'index'         => 'SystemUploadCate/index',
                'add'           => 'SystemUploadCate/add',
                'edit'          => 'SystemUploadCate/edit',
                'del'           => 'SystemUploadCate/del',
            ],
        ];
        return parent::successRes($data);
    }

    /**
     * 系统登录
     *
     * @return void
     */
    public function login(Request $request)
    {
        // 获取数据
        $post = $request->post();
        // 数据验证
        hpValidate(ValidateSystemAdmin::class, $post, 'login');

        // 查询数据
        $where = [
            'username'          => $post['username']
        ];
        $adminModel = SystemAdmin::with(['role'])->where($where)->find();
        if (!$adminModel) {
            throw new Exception('登录账号错误');
        }
        // 验证登录密码
        if (!Password::passwordVerify((string) $post['password'], (string)$adminModel->password)) {
            throw new Exception('登录密码错误');
        }
        if ($adminModel->status == 0) {
            throw new Exception('该用户已被冻结');
        }
        $session = $request->session();
        $session->set('hp_admin', $adminModel);

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
     *
     * @return void
     */
    public function user()
    {
        $data = hp_admin([
            'username',
            'nickname',
            'headimg',
            'role',
        ]);
        return parent::successRes($data);
    }

    /**
     * 获取菜单数据
     *
     * @return void
     */
    public function menus()
    {
        $admin = hp_admin();

        $data = VueRoutesMgr::run($admin);
        return parent::successRes($data);
    }

    /**
     * 退出登录
     *
     * @param Request $request
     * @return void
     */
    public function loginout(Request $request)
    {
        $session = $request->session();
        $session->delete('hp_admin');
        return parent::success('成功退出');
    }
}
