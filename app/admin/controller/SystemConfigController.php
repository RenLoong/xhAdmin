<?php

namespace app\admin\controller;

use app\common\builder\FormBuilder;
use app\common\model\SystemConfig;
use app\BaseController;
use app\common\enum\ConfigGroupCol;
use app\common\service\UploadService;
use Exception;
use support\Request;

/**
 * 配置项
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-07
 */
class SystemConfigController extends BaseController
{
    // 组件类型
    private $customComponent = ['uploadify'];
    private $extraOptions = ['checkbox', 'radio', 'select'];

    /**
     * 系统配置
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function form(Request $request)
    {
        if ($request->method() == 'PUT') {
            $post = request()->post();
            $settings = config('settings');
            $settings = array_column($settings, 'children');
            $data = [];
            foreach ($settings as $value) {
                $data = array_merge($data, $value);
            }
            foreach ($data as $value) {
                # 保存数据
                $dataValue = empty($value['name']) ? '' : $post[$value['name']];
                # 查询数据
                $where = [
                    'name'          => $value['name'],
                    'store_id'      => null,
                    'saas_appid'    => null,
                ];
                $model = SystemConfig::where($where)->find();
                if (!$model) {
                    $model = new SystemConfig;
                    $model->name = $value['name'];
                }
                // 更新数据
                $model->value = $dataValue;
                # 验证是否附件库
                if (is_array($model['value']) && $value['component'] === 'uploadify') {
                    $files = [];
                    foreach ($dataValue as $k => $v) {
                        $files[$k] = UploadService::path($v);
                    }
                    $uploadPath   = implode(',', $files);
                    $model->value = $uploadPath;
                }
                if ($model->save() === false) {
                    return $this->fail('保存失败');
                }
            }
            return $this->success('保存成功');
        }
        $dataTabs = $this->getTabs();
        $builder  = new FormBuilder;
        $builder  = $builder->initTabs($dataTabs['active'], [
            'props' => [
                // 选项卡样式
                'type' => 'line'
            ],
        ]);
        $builder  = $builder->setMethod('PUT');
        foreach ($dataTabs['tabs'] as $value) {
            $builder = $builder->addTab(
                $value['name'],
                $value['title'],
                $value['children']
            );
        }
        $data = $builder->endTabs()->create();
        return parent::successRes($data);
    }

    /**
     * 获取配置分组
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    private function getTabs(): array
    {
        $settingsPath = base_path('/config/settings.php');
        if (!file_exists($settingsPath)) {
            throw new Exception('配置文件不存在');
        }
        $settings = config('settings');
        if (empty($settings)) {
            return [];
        }
        $list = [];
        foreach ($settings as $key => $value) {
            if (empty($value['name'])) {
                throw new Exception('分组标识不能为空');
            }
            if (empty($value['title'])) {
                throw new Exception('分组名称不能为空');
            }
            $list[$key]['name'] = $value['name'];
            $list[$key]['title'] = $value['title'];
            # 默认布局模式
            $col = 24;
            if (!empty($value['layout_col'])) {
                $colData = ConfigGroupCol::getValue($value['layout_col']);
                if (empty($colData['col'])) {
                    throw new Exception('布局模式错误');
                }
                $col = (int)$colData['col'];
            }
            if (!empty($value['children']) && is_array($value['children'])) {
                $list[$key]['children'] = $this->getConfig($value['children'], $col);
            }
        }
        $active         = 0;
        $data['active'] = isset($list[$active]['name']) ? $list[$active]['name'] : '';
        $data['tabs']   = $list;
        return $data;
    }

    /**
     * 获取系统配置
     * @param array $list
     * @param int $col
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function getConfig(array $list, int $col): array
    {
        # 查询数据
        $configNames = array_column($list, 'name');
        $where  = [
            ['name', 'in', $configNames],
            ['store_id', '=', null],
            ['saas_appid', '=', null],
        ];
        $dataValue = SystemConfig::where($where)->column('value', 'name');
        $config  = [];
        $builder = new FormBuilder;
        foreach ($list as $value) {
            # 数据验证
            hpValidate(\app\admin\validate\SystemConfig::class, $value);
            # 设置数据
            $configValue = empty($dataValue[$value['name']]) ? '' : $dataValue[$value['name']];
            # 验证扩展组件
            if (in_array($value['component'],$this->extraOptions)) {
                if (empty($value['extra'])) {
                    throw new Exception("[{$value['name']}] - 扩展数据不能为空");
                }
                if (empty($value['extra']['options'])) {
                    throw new Exception("[{$value['name']}] - 扩展的选项数据不能为空");
                }
            }
            # 附件库参数设置
            if ($value['component'] == 'uploadify') {
                # 重设的模型数据
                if ($configValue) {
                    $tempConfig  = array_filter(explode(',', $configValue));
                    $configValue = UploadService::urls($tempConfig);
                } else {
                    $configValue = [];
                }
            }
            # 设置组件参数
            if (in_array($value['component'], $this->customComponent)) {
                # 设置底部描述
                if (!empty($value['placeholder'])) {
                    $value['extra']['props']['prompt']['text'] = $value['placeholder'];
                }
                # 设置布局模式
                $value['extra']['props']['col'] = $col;
                # 自定义组件
                $builder->addComponent(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $value['extra']
                );
            } else {
                # 设置底部描述
                if (!empty($value['placeholder'])) {
                    $value['extra']['prompt']['text'] = $value['placeholder'];
                }
                # 设置布局模式
                $value['extra']['props']['col'] = $col;
                # 普通组件
                $builder->addRow(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $value['extra']
                );
            }
        }
        $config = $builder->getBuilder()->formRule();
        return $config;
    }
}