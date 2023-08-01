<?php

namespace app\admin\controller;

use app\common\builder\FormBuilder;
use app\common\builder\ListBuilder;
use app\admin\model\Store;
use app\common\model\StoreApp;
use app\common\model\Users;
use app\admin\validate\Store as ValidateStore;
use app\BaseController;
use app\common\enum\PlatformTypes;
use app\common\service\UploadService;
use Exception;
use support\Request;
use think\facade\Db;

/**
 * 租户管理
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 */
class StoreController extends BaseController
{
    /**
     * 模型
     * @var Store
     */
    protected $model;

    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function __construct()
    {
        $this->model = new Store;
    }

    /**
     * 表格
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function indexGetTable(Request $request)
    {
        $platformAssets = PlatformTypes::toArray();
        foreach ($platformAssets as $key => $value) {
            $platformAssets[$key]['title'] = $value['text'];
            $platformAssets[$key]['field'] = $value['value'];
        }
        $builder = new ListBuilder;
        $data    = $builder
            ->addActionOptions('操作', [
                'width' => 100,
                'params' => [
                    'group' => true
                ]
            ])
            ->pageConfig()
            ->editConfig()
            ->rowConfig([
                'height' => 70
            ])
            ->addTopButton('add', '开通', [
                'type' => 'page',
                'api' => 'admin/Store/add',
                'path' => '/Store/add',
            ], [
                'title' => '开通租户',
            ], [
                'type' => 'success'
            ])
            ->addRightButton(
                'toStore',
                '管理租户',
                [
                    'type' => 'link',
                    'api' => 'admin/Store/login',
                    'aliasParams' => [
                        'id' => 'store_id'
                    ],
                ],
                [],
                [
                    'type' => 'warning',
                    'icon' => 'CodeSandboxOutlined'
                ]
            )
            ->addRightButton('storeApp', '授权应用', [
                'type' => 'page',
                'api' => 'admin/StoreApp/index',
                'path' => '/StoreApp/index',
                'aliasParams' => [
                    'id' => 'store_id'
                ],
            ], [], [
                'type' => 'warning',
                'icon' => 'CompassOutlined'
            ])
            ->addRightButton('copyrightSet', '版权设置', [
                'type' => 'page',
                'api' => 'admin/Store/copyrightSet',
                'path' => '/Store/copyrightSet',
            ], [
                'title' => '租户版权设置',
            ], [
                'type' => 'primary',
                'icon' => 'EditOutlined'
            ])
            ->addRightButton('projects', '项目管理', [
                'type' => 'page',
                'api' => 'admin/StoreProject/index',
                'path' => '/StoreProject/index',
            ], [], [
                'type' => 'info',
                'icon' => 'DesktopOutlined'
            ])
            ->addRightButton('edit', '修改租户', [
                'type' => 'page',
                'api' => 'admin/Store/edit',
                'path' => '/Store/edit',
            ], [
                'title' => '修改租户',
            ], [
                'type' => 'primary',
                'icon' => 'EditOutlined'
            ])
            ->addRightButton('del', '删除租户', [
                'type' => 'confirm',
                'api' => 'admin/Store/del',
                'method' => 'delete',
            ], [
                'title' => '温馨提示',
                'content' => '该数据删除将不可恢复，请谨慎操作',
            ], [
                'type' => 'error',
                'icon' => 'RestOutlined'
            ])
            ->addColumn('title', '名称')
            ->addColumn('username', '账号')
            ->addColumnEle('logo', '图标', [
                'width' => 60,
                'params' => [
                    'type' => 'image',
                ],
            ])
            ->addColumnEle('status', '状态', [
                'width' => 100,
                'params' => [
                    'type' => 'switch',
                    'api' => '/admin/Store/rowEdit',
                    'checked' => [
                        'text' => '正常',
                        'value' => '20'
                    ],
                    'unchecked' => [
                        'text' => '冻结',
                        'value' => '10'
                    ]
                ],
            ])
            ->addColumnEle('surplusNum', '租户资产：已创建/总数量', [
                'minWidth' => '100px',
                'params' => [
                    'type' => 'assets',
                    'resource' => $platformAssets,
                ]
            ])
            ->addColumnEdit('expire_time', '过期时间', [
                'params' => [
                    'api' => '/admin/Store/rowEdit'
                ],
                'editRender' => [
                    'attrs' => [
                        'type' => 'date',
                        'transfer' => true,
                    ],
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function index(Request $request)
    {
        $model = $this->model;
        $data  = $model->order(['id' => 'desc'])
            ->paginate()
            ->each(function ($e) {
                return $e;
            })
            ->toArray();
        return parent::successRes($data);
    }

    /**
     * 添加
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function add(Request $request)
    {
        $validate = ValidateStore::class;
        if ($request->method() == 'POST') {
            $post = $request->post();
            $post['status'] = '20';

            // 数据验证
            hpValidate($validate, $post, 'add');

            $post['logo'] = UploadService::path($post['logo']);
            $model        = $this->model;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('POST')
            ->addRow('username', 'input', '租户账号', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('title', 'input', '租户名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('expire_time', 'input', '过期时间', '', [
                'col' => [
                    'span' => 12
                ],
                'type' => 'date',
                'placeholder' => ''
            ])
            ->addRow('contact', 'input', '联系人姓名', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('mobile', 'input', '联系电话', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addComponent('logo', 'uploadify', '租户图标', '', [
                'col' => [
                    'span' => 6
                ],
                'props' => [
                    'type' => 'image',
                    'format' => ['jpg', 'png', 'gif']
                ],
            ])
            ->addRow('remarks', 'textarea', '租户备注', '', [
                'col' => [
                    'span' => 18
                ],
            ])
            ->addRow('wechat', 'input', '公众号数量', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('mini_wechat', 'input', '微信小程序', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('douyin', 'input', '抖音小程序', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('h5', 'input', '网页应用', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('app', 'input', 'APP应用', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('other', 'input', '其他应用', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->formValidate($validate)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 修改
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function edit(Request $request)
    {
        $id    = $request->get('id');
        $where = [
            'id' => $id
        ];
        $model = $this->model;
        $model = $model->where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($request->method() == 'PUT') {
            $post = $request->post();

            // 数据验证
            hpValidate(ValidateStore::class, $post, 'edit');

            # 验证是否空密码
            if (empty($post['password']) && isset($post['password'])) {
                unset($post['password']);
            }
            $post['logo'] = UploadService::path($post['logo']);
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $formData                = $model->toArray();
        $formData['expire_time'] = date('Y-m-d', strtotime($formData['expire_time']));
        $builder                 = new FormBuilder;
        $data                    = $builder
            ->setMethod('PUT')
            ->addRow('username', 'input', '租户账号', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('title', 'input', '租户名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('expire_time', 'input', '过期时间', '', [
                'col' => [
                    'span' => 12
                ],
                'type' => 'date',
                'placeholder' => ''
            ])
            ->addRow('contact', 'input', '联系人姓名', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('mobile', 'input', '联系电话', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addComponent('logo', 'uploadify', '租户图标', '', [
                'col' => [
                    'span' => 6
                ],
                'props' => [
                    'type' => 'image',
                    'format' => ['jpg', 'png', 'gif']
                ],
            ])
            ->addRow('remarks', 'textarea', '租户备注', '', [
                'col' => [
                    'span' => 18
                ],
            ])
            ->addRow('wechat', 'input', '公众号数量', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('mini_wechat', 'input', '微信小程序', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('douyin', 'input', '抖音小程序', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('h5', 'input', '网页应用', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('app', 'input', 'APP应用', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('other', 'input', '其他应用', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->setFormData($formData)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 版权设置
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function copyrightSet(Request $request)
    {
        $id    = $request->get('id');
        $where = [
            'id' => $id
        ];
        $model = $this->model;
        $model = $model->where($where)->find();
        if (!$model) {
            return parent::fail('该数据不存在');
        }
        if ($request->method() === 'PUT') {
            $post = $request->post();
            if (!$model->save($post)) {
                return $this->fail('保存版权设置失败');
            }
            return $this->success('保存版权设置成功');
        }
        $formData = $model->toArray();
        $builder  = new FormBuilder;
        $data     = $builder
            ->setMethod('PUT')
            ->addRow('title', 'input', '租户名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('copyright_service', 'input', '专属客服', '', [
                'placeholder' => '租户首页展示的专属客服，如不填写，则按照系统配置中的显示',
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('copyright_tutorial', 'textarea', '系统教程', '', [
                'placeholder' => '租户首页展示的系统教程，如不填写，则按照系统配置中的显示',
            ])
            ->setFormData($formData)
            ->create();
        return parent::successRes($data);
    }

    /**
     * 删除
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-30
     */
    public function del(Request $request)
    {
        $id    = $request->post('id');
        $model = $this->model;

        # 开启事务
        Db::startTrans();
        try {
            # 查询租户信息
            $where = [
                'id' => $id
            ];
            $model = $model->where($where)->find();
            if (!$model) {
                throw new Exception('该数据不存在');
            }
            # 删除租户下的所有用户
            $users = Users::where($where)->select();
            foreach ($users as $userModel) {
                if (!$userModel->delete()) {
                    throw new Exception('删除租户用户失败');
                }
            }
            # 删除平台下应用
            $apps = StoreApp::where($where)->select();
            foreach ($apps as $appModel) {
                if (!$appModel->delete()) {
                    throw new Exception('删除租户应用失败');
                }
            }
            # 删除租户
            if (!$model->delete()) {
                throw new Exception('删除租户失败');
            }
            Db::commit();
            return $this->success('删除成功');
        } catch (\Throwable $e) {
            Db::rollback();
            return $this->fail($e->getMessage());
        }
    }

    /**
     * 登录租户平台
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function login(Request $request)
    {
        $store_id = $request->get('store_id');
        if (!$store_id) {
            return parent::fail('该数据不存在');
        }
        // 查询数据
        $where      = [
            'id' => $store_id
        ];
        $adminModel = Store::where($where)->find();
        if (!$adminModel) {
            return parent::fail('登录错误');
        }
        $sessionId = $request->sessionId();
        $session   = $request->session();
        $session->set('hp_store', $adminModel->toArray());

        $url = "store/#/Index/index?token={$sessionId}";
        return $this->successRes(['url' => $url]);
    }
}