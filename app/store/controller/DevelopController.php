<?php

namespace app\store\controller;

use app\common\BaseController;
use app\common\builder\FormBuilder;
use app\common\enum\PlatformTypes;
use app\common\enum\SettingsEnum;
use app\common\enum\YesNoEum;
use app\store\model\StoreApp;
use app\store\validate\Develop;
use FormBuilder\Factory\Elm;
use support\Request;
use think\facade\Db;

/**
 * 开发者项目
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
class DevelopController extends BaseController
{
    /**
     * 模型
     * @var StoreApp
     */
    public $model;

    /**
     * 插件模板路径
     * @var string
     */
    protected $pluginTplPath = null;

    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function initialize()
    {
        $this->model         = new StoreApp;
        $this->pluginTplPath = base_path() . 'common/template/plugin/';
    }

    /**
     * 创建开发者应用
     * @param \support\Request $request
     * @return mixed
     * @author John
     */
    public function create(Request $request)
    {
        if ($request->method() === 'POST') {
            $post             = $request->post();
            $store            = $request->user;
            $post['store_id'] = $store['id'];
            $post['status']   = '20';

            hpValidate(Develop::class, $post);

            # 检查应用是否存在
            $teamName   = $post['team'];
            $appName    = ucfirst($post['name']);
            $pluginName = "{$teamName}{$appName}";
            $pluginPath = root_path() . 'plugin/' . $pluginName;
            if (is_dir($pluginPath)) {
                return $this->fail("该应用【{$pluginName}】已存在");
            }

            Db::startTrans();
            try {
                # 复制应用文件
                $this->copyTplFile($pluginPath, $pluginName, $post);
                # 替换文件内容
                $this->strReplaceTpl($pluginPath, $pluginName, $post);
                # 创建应用数据库
                $this->createPluginTable($pluginPath, $pluginName, $post);
                # 创建应用数据
                $this->createPluginData($pluginPath, $pluginName, $post);
                Db::commit();
            } catch (\Throwable $e) {
                Db::rollback();
                return $this->fail($e->getMessage());
            }
            return $this->success('操作成功');
        }
        $builder = $this->formView();
        $data    = $builder->create();
        return parent::successRes($data);
    }

    /**
     * 创建插件数据
     * @param string $pluginPath
     * @param string $pluginName
     * @param array $data
     * @return void
     * @author John
     */
    protected function createPluginData(string $pluginPath, string $pluginName, array $data)
    {
    }

    /**
     * 创建插件数据库
     * @param string $pluginPath
     * @param string $pluginName
     * @param array $data
     * @return void
     * @author John
     */
    protected function createPluginTable(string $pluginPath, string $pluginName, array $data)
    {
    }

    /**
     * 替换文件内容
     * @param string $pluginPath
     * @param string $pluginName
     * @param array $data
     * @return void
     * @author John
     */
    protected function strReplaceTpl(string $pluginPath, string $pluginName, array $data)
    {
    }

    /**
     * 复制应用文件
     * @param string $pluginPath
     * @param string $pluginName
     * @param array $data
     * @return void
     * @author John
     */
    protected function copyTplFile(string $pluginPath, string $pluginName, array $data)
    {
        print_r(request()->post());exit;
        # 普通文件
        $ordinary = [
            'api/Created.tpl',
            'api/Install.tpl',
            'api/Login.tpl',
            'app/admin/controller/IndexController.tpl',
            'app/admin/controller/PublicsController.tpl',
            'app/admin/controller/SettingsController.tpl',
            'app/admin/controller/UploadCateController.tpl',
            'app/admin/controller/UploadController.tpl',
            'app/controller/IndexController.tpl',
            'config/middleware.tpl',
            'config/settings.tpl',
            'config/task.tpl',
            'package/remarks.txt',
            'public/remarks.txt',
        ];
        # 文章系统
        $article = [
            'app/admin/controller/ArtCategoryController.tpl',
            'app/admin/controller/ArticlesController.tpl',
        ];
        # 单页应用
        $onePage = [
            'app/admin/controller/OnePageController.tpl',
        ];
        # 系统配置
        $setting = [];
        # 权限管理
        $auth   = [
            'app/admin/controller/MenusController.tpl',
            'app/admin/controller/RolesController.tpl',
            'app/admin/controller/AdminController.tpl',
        ];

        # 合并文件
        $data = [];
        foreach ($data as $path) {
            $filePath = $pluginPath . '/' . $path;
            $dirPath  = dirname($filePath);
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
            if (file_exists($this->pluginTplPath . $path)) {
                print_r(basename($filePath));
                exit;
                copy($this->pluginTplPath . $path, $filePath);
            }
        }
    }

    /**
     * 表单视图
     * @return \app\common\builder\FormBuilder
     * @author John
     */
    protected function formView(): FormBuilder
    {
        $builder = new FormBuilder;
        $builder->setMethod('POST');
        $builder->addRow('title', 'input', '项目名称', '', [
            'col' => 12,
        ]);
        $builder->addRow('platform', 'select', '项目类型', '', [
            'col' => 12,
            'options' => PlatformTypes::getOptions()
        ]);
        $builder->addRow('team', 'input', '团队标识', '', [
            'col' => 12,
        ]);
        $builder->addRow('name', 'input', '应用标识', '', [
            'col' => 12,
        ]);
        $builder->addRow('is_page', 'radio', '单页管理', '10', [
            'col' => 12,
            'options' => YesNoEum::getOptions()
        ]);
        $builder->addRow('is_article', 'radio', '文章系统', '10', [
            'col' => 12,
            'options' => YesNoEum::getOptions()
        ]);
        $builder->addRow('is_system', 'checkbox', '系统配置', ['10'], [
            'col' => 12,
            'options' => SettingsEnum::getOptions()
        ]);
        $builder->addRow('is_auth', 'radio', '权限管理', '10', [
            'col' => 12,
            'options' => YesNoEum::getOptions(),
            'control' => [
                [
                    'value' => '20',
                    'where' => '==',
                    'rule' => [
                        Elm::input('auth_params', '超管账号')
                            ->col([
                                'span' => 12
                            ]),
                        Elm::input('password', '登录密码')->col([
                            'span' => 12
                        ]),
                    ]
                ],
            ],
        ]);
        return $builder;
    }
}