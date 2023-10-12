<?php

namespace app\admin\controller;

use app\common\builder\FormBuilder;
use app\common\builder\ListBuilder;
use app\admin\model\Store;
use app\common\model\StoreApp;
use app\common\model\Users;
use app\admin\validate\Store as ValidateStore;
use app\common\BaseController;
use app\common\enum\PlatformTypes;
use app\common\service\UploadService;
use Exception;
use support\Request;
use think\facade\Db;
use think\facade\Session;

/**
 * 渠道管理
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
            ->addActionOptions('操作',[
                'width'     => 380
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
                'title' => '开通渠道',
            ], [
                'type' => 'primary'
            ])
            ->addRightButton(
                'toStore',
                '管理',
                [
                    'type'          => 'link',
                    'api'           => 'admin/Store/login',
                    'aliasParams'   => [
                        'id' => 'store_id'
                    ],
                ],
                [],
                [
                    'type' => 'primary',
                    'icon' => 'CodeSandboxOutlined'
                ]
            )
            ->addRightButton('storeApp', '授权', [
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
                'group'     => true,
                'type'      => 'page',
                'api'       => 'admin/Store/copyrightSet',
                'path'      => '/Store/copyrightSet',
            ], [
                'title' => '渠道版权设置',
            ], [
                'type' => 'primary',
                'icon' => 'EditOutlined'
            ])
            ->addRightButton('projects', '项目', [
                'type' => 'page',
                'api' => 'admin/StoreProject/index',
                'path' => '/StoreProject/index',
            ], [], [
                'type' => 'info',
                'icon' => 'DesktopOutlined'
            ])
            ->addRightButton('edit', '修改渠道', [
                'group'     => true,
                'type' => 'page',
                'api' => 'admin/Store/edit',
                'path' => '/Store/edit',
            ], [
                'title' => '修改渠道',
            ], [
                'type' => 'primary',
                'icon' => 'EditOutlined'
            ])
            ->addRightButton('del', '删除渠道', [
                'group'     => true,
                'type' => 'confirm',
                'api' => 'admin/Store/del',
                'method' => 'delete',
            ], [
                'type' => 'error',
                'title' => '温馨提示',
                'content' => '该数据删除将不可恢复，请谨慎操作',
            ], [
                'type' => 'danger',
                'icon' => 'RestOutlined'
            ])
            ->addScreen('keyword','input','渠道名称')
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
            ->addColumnEle('surplusNum', '渠道资产：已创建/总数量', [
                'width' => 330,
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
        $keyword = $request->get('keyword','');
        $where   = [];
        if ($keyword) {
            $where[]        = ['title','like', '%' . $keyword . '%'];
        }
        $model = $this->model;
        $data  = $model->where($where)
            ->order(['id' => 'desc'])
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
            $post           = $request->post();
            $post['status'] = '20';

            // 数据验证
            hpValidate($validate, $post, 'add');

            $model = $this->model;
            if (!$model->save($post)) {
                return parent::fail('保存失败');
            }
            return parent::success('保存成功');
        }
        $builder = new FormBuilder;
        $data    = $builder
            ->setMethod('POST')
            ->addRow('username', 'input', '渠道账号', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('title', 'input', '渠道名称', '', [
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
            ->addComponent('logo', 'uploadify', '渠道图标', '', [
                'col' => [
                    'span' => 6
                ],
                'props' => [
                    'type' => 'image',
                    'format' => ['jpg', 'png', 'gif']
                ],
            ])
            ->addRow('remarks', 'textarea', '渠道备注', '', [
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
            ->addRow('username', 'input', '渠道账号', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('password', 'input', '登录密码', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('title', 'input', '渠道名称', '', [
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
            ->addComponent('logo', 'uploadify', '渠道图标', '', [
                'col' => [
                    'span' => 6
                ],
                'props' => [
                    'type' => 'image',
                    'format' => ['jpg', 'png', 'gif']
                ],
            ])
            ->addRow('remarks', 'textarea', '渠道备注', '', [
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
            ->addRow('title', 'input', '渠道名称', '', [
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('copyright_service', 'input', '专属客服', '', [
                'placeholder' => '渠道首页展示的专属客服，如不填写，则按照系统配置中的显示',
                'col' => [
                    'span' => 12
                ],
            ])
            ->addRow('copyright_tutorial', 'textarea', '系统教程', '', [
                'placeholder' => '渠道首页展示的系统教程，如不填写，则按照系统配置中的显示',
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
            # 查询渠道信息
            $where = [
                'id' => $id
            ];
            $model = $model->where($where)->find();
            if (!$model) {
                throw new Exception('该数据不存在');
            }
            # 删除渠道下的所有用户
            $users = Users::where($where)->select();
            foreach ($users as $userModel) {
                if (!$userModel->delete()) {
                    throw new Exception('删除渠道用户失败');
                }
            }
            # 删除平台下应用
            $apps = StoreApp::where($where)->select();
            foreach ($apps as $appModel) {
                if (!$appModel->delete()) {
                    throw new Exception('删除渠道应用失败');
                }
            }
            # 删除渠道
            if (!$model->delete()) {
                throw new Exception('删除渠道失败');
            }
            Db::commit();
            return $this->success('删除成功');
        } catch (\Throwable $e) {
            Db::rollback();
            return $this->fail($e->getMessage());
        }
    }

    /**
     * 登录渠道平台
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

        // 构建令牌
        Session::set('XhAdminStore', $adminModel);

        $url = "store/#/?token=XhAdminStore";
        return $this->successRes(['url' => $url]);
    }
}