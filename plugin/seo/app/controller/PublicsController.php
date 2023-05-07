<?php

namespace plugin\seo\app\controller;

use app\store\model\Store;
use app\store\model\StoreMenus;
use app\utils\Password;
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
        $data = [
            'web_name'       => getHpConfig('web_name'),
            'web_title'      => '租户登录',
            'web_logo'       => getHpConfig('web_logo'),
            // 登录页链接
            'login_link'     => [
                'register'    => '',
                'forget'      => '',
                'other_login' => [],
            ],
            // 公用接口
            'public_api'     => [
                'login'     => "{$this->moduleName}/Publics/login",
                'loginout'  => "{$this->moduleName}/Publics/loginout",
                'user'      => "{$this->moduleName}/Publics/user",
                'menus'     => "{$this->moduleName}/Publics/menus",
                'clear'     => "{$this->moduleName}/Index/clear",
                'lock'      => "{$this->moduleName}/Index/lock",
                "user_edit" => "{$this->moduleName}/Store/edit",
            ],
            // 远程组件
            'remote_url'     => [
                // [
                //     'title'  => '用户注册',
                //     'path'   => '/register',
                //     'remote' => 'remote/register'
                // ],
            ],
            // 附件库API
            'uploadify_api'  => [
                'index'  => "{$this->moduleName}/SystemUpload/index",
                'upload' => "{$this->moduleName}/SystemUpload/upload",
                'edit'   => "{$this->moduleName}/SystemUpload/edit",
                'del'    => "{$this->moduleName}/SystemUpload/del",
                'move'   => "{$this->moduleName}/SystemUpload/move",
            ],
            // 附件库分类
            'uploadify_cate' => [
                'index' => "{$this->moduleName}/SystemUploadCate/index",
                'add'   => "{$this->moduleName}/SystemUploadCate/add",
                'edit'  => "{$this->moduleName}/SystemUploadCate/edit",
                'del'   => "{$this->moduleName}/SystemUploadCate/del",
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
            'username'          => $post['username']
        ];
        $adminModel = Store::where($where)->find();
        if (!$adminModel) {
            throw new Exception('登录账号错误');
        }
        // 验证登录密码
        if (!Password::passwordVerify((string) $post['password'], (string)$adminModel->password)) {
            throw new Exception('登录密码错误');
        }
        if ($adminModel->status === '0') {
            throw new Exception('该用户已被冻结');
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
    public function user()
    {
        $admin_id = hp_admin_id('hp_store');
        if (!$admin_id) {
            return parent::success('该用户不存在');
        }
        $where    = [
            'id'    => $admin_id
        ];
        $model      = Store::with(['grade'])->where($where)->find();
        $storeModel  = $model->toArray();
        $data       = [
            'id'        => $storeModel['id'],
            'nickname'  => $storeModel['title'],
            'headimg'   => $storeModel['logo'],
            'role'      => [
                'title' => $storeModel['grade']['title']
            ],
        ];
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
        $order = [
            'sort'      => 'asc',
            'id'        => 'asc'
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
        $list = StoreMenus::order($order)->field($field)->select()->toArray();
        $data = [
            'active'        => 'Index/index',
            'list'          => $list
        ];
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
        $session->delete('hp_store');
        return parent::success('成功退出');
    }
}
