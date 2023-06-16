<?php

namespace app\admin\controller;

use app\admin\builder\FormBuilder;
use app\admin\model\SystemUpload;
use app\BaseController;
use app\service\Upload;
use support\Request;

/**
 * @title 附件管理
 * @desc 默认使用插件：https://github.com/shopwwi/webman-filesystem
 * 在线手册:https://www.workerman.net/plugin/19
 * @author 楚羽幽 <admin@hangpu.net>
 */
class SystemUploadController extends BaseController
{
    // 执行驱动方法
    private $driverTabsName = [
        'public' => '本地附件',
        'oss' => '阿里云储存',
        'qiniu' => '七牛云储存',
        'cos' => '腾讯云储存',
    ];

    /**
     * 附件库设置
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function config(Request $request)
    {
        $config  = config('plugin.shopwwi.filesystem.app', []);
        $storage = isset($config['storage']) ? $config['storage'] : [];
        if ($request->method() === 'PUT') {
            $post       = $request->post();
            $tpl        = app_path('/admin/tpl/upload.tpl');
            $tplContent = file_get_contents($tpl);
            $strTpl     = [
                "{default}",
                "{max_size}",
                "{ext_yes}",
                "{ext_no}",
            ];
            $default    = isset($post['default']) ? $post['default'] : 'public';
            $max_size   = isset($post['max_size']) ? (int) $post['max_size'] : 50;
            # 允许上传格式
            $ext_yes = isset($post['ext_yes']) ? $post['ext_yes'] : '';
            $ext_yes = array_filter(explode(',', $ext_yes));
            $ext_yes = str_replace(["array (", ')'], ['[', ']'], var_export($ext_yes, true));
            $ext_yes = str_replace("\n", "", $ext_yes);
            # 禁止上传格式
            $ext_no      = isset($post['ext_no']) ? $post['ext_no'] : '';
            $ext_no      = array_filter(explode(',', $ext_no));
            $ext_no      = str_replace(["array (", ')'], ['[', ']'], var_export($ext_no, true));
            $ext_no      = str_replace("\n", "", $ext_no);
            # 移除字段
            unset($post['default']);
            unset($post['max_size']);
            unset($post['ext_yes']);
            unset($post['ext_no']);
            $strValue    = [
                $default,
                $max_size,
                $ext_yes,
                $ext_no
            ];
            $dataContent = str_replace($strTpl, $strValue, $tplContent);
            foreach ($post as $fieldKey => $value) {
                $dataContent = str_replace("{{$fieldKey}}", $value, $dataContent);
            }
            $uploadConfig = base_path('/config/plugin/shopwwi/filesystem/app.php');
            file_put_contents($uploadConfig, $dataContent);
            return $this->success('配置保存完成');
        }
        $builder       = new FormBuilder;
        $default       = isset($config['default']) ? $config['default'] : 'public';
        $max_size      = isset($config['max_size']) ? $config['max_size'] / 1024 / 1024 : 50;
        $ext_yes       = isset($config['ext_yes']) && is_array($config['ext_yes']) ? implode(',', $config['ext_yes']) : '';
        $ext_no        = isset($config['ext_no']) && is_array($config['ext_no']) ? implode(',', $config['ext_no']) : '';
        $builder       = $builder->initTabs('config', [
            'props' => [
                // 选项卡样式
                'type' => 'line'
            ],
        ]);
        $selectOptions = [];
        foreach ($storage as $driverName => $value) {
            $selectOptions[] = [
                'label' => $this->driverTabsName[$driverName],
                'value' => $driverName
            ];
        }
        if (isset($storage['public'])) {
            unset($storage['public']);
        }
        $builder  = $builder->setMethod('PUT');
        $children = new FormBuilder;
        $children->addRow('default', 'select', '当前使用', $default, [
            'options' => $selectOptions
        ]);
        $children->addRow('max_size', 'input', '单个文件上传大小(MB)', $max_size);
        $children->addRow('ext_yes', 'input', '允许上传类型', $ext_yes, [
            'placeholder' => '示例：image/jpg,image/jpeg,image/png,image/gif，不允许上传文件类型 为空则不限制，多个使用小写逗号隔开'
        ]);
        $children->addRow('ext_no', 'input', '不允许上传类型', $ext_no, [
            'placeholder' => '示例：image/jpg,image/jpeg,image/png,image/gif，不允许上传文件类型 为空则不限制，多个使用小写逗号隔开'
        ]);
        $builder = $builder->addTab(
            'config',
            '基本设置',
            $children->getBuilder()->formRule(),
        );
        foreach ($storage as $driver => $driverConfig) {
            $children        = [];
            $childrenBuilder = new FormBuilder;
            foreach ($driverConfig as $fieldKey => $fieldValue) {
                if (!in_array($fieldKey, ['driver', 'root'])) {
                    $field = "{$driver}_{$fieldKey}";
                    $childrenBuilder->addRow($field, 'input', $fieldKey, $fieldValue);
                }
            }
            $children = $childrenBuilder->getBuilder()->formRule();
            $title    = isset($this->driverTabsName[$driver]) ? $this->driverTabsName[$driver] : '错误';
            $builder  = $builder->addTab(
                $driver,
                $title,
                $children
            );
        }
        $data = $builder->endTabs()->create();
        return parent::successRes($data);
    }

    /**
     * 获取附件列表
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function index(Request $request)
    {
        list($where, $orderBy, $limit) = $this->getParams($request);
        empty($orderBy) && $orderBy = ['update_at' => 'desc'];
        $where = array_merge($where, [
            ['store_id','=',null],
            ['platform_id','=',null],
            ['appid','=',null],
            ['uid','=',null],
        ]);
        $data   = SystemUpload::with(['category'])
            ->where($where)
            ->order($orderBy)
            ->paginate($limit)
            ->toArray();
        return parent::successRes($data);
    }

    /**
     * 修改附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function edit(Request $request)
    {
        $id    = $request->get('id', '');
        $where = [
            ['id', '=', $id]
        ];
        $model = SystemUpload::where($where)->find();
        if (!$model) {
            return parent::fail('该附件不存在');
        }
        if ($request->method() === 'PUT') {
            $post = $request->post();
            if (!$model->save($post)) {
                return parent::fail('修改失败');
            }
            return parent::success('修改成功');
        } else {
            $builder = new FormBuilder;
            $data    = $builder
                ->setMethod('PUT')
                ->addRow('title', 'input', '附件名称')
                ->addRow('path', 'input', '文件地址', '', [
                    'disabled' => true,
                ])
                ->addRow('filename', 'input', '文件名称', '', [
                    'disabled' => true,
                    'col' => [
                        'span' => 12
                    ]
                ])
                ->addRow('format', 'input', '文件格式', '', [
                    'disabled' => true,
                    'col' => [
                        'span' => 12
                    ]
                ])
                ->addRow('size_format', 'input', '文件大小', '', [
                    'disabled' => true,
                    'col' => [
                        'span' => 12
                    ]
                ])
                ->addRow('adapter', 'input', '选定器', '', [
                    'disabled' => true,
                    'col' => [
                        'span' => 12
                    ]
                ])
                ->setData($model)
                ->create();
            return parent::successRes($data);
        }
    }

    /**
     * 移动选中附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function move(Request $request)
    {
        $cate_id = $request->post('cate_id');
        $ad_id   = $request->post('ids');
        if (!$cate_id) {
            return parent::fail('请选择移动的分类');
        }
        if (!$ad_id) {
            return parent::fail('附件选择错误');
        }
        if (!is_array($ad_id)) {
            return parent::fail('请选择移动的附件');
        }
        $where[] = ['id', 'in', $ad_id];
        SystemUpload::where($where)->save(['cid' => $cate_id]);
        return parent::success('附件移动完成');
    }

    /**
     * 删除选中附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function del(Request $request)
    {
        $id = (int) $request->post('id', 0);
        if (!$id) {
            return parent::fail('请选择需要删除的附件');
        }
        if (!Upload::delete($id)) {
            return parent::fail('删除失败');
        }
        return parent::success('删除完成');
    }


    /**
     * 上传附件
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $cid  = (int) $request->post('cid');
        if (!$data = Upload::upload($file, $cid)) {
            return parent::fail('上传失败');
        }
        return parent::successFul('上传成功', $data);
    }
}