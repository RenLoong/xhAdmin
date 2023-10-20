<?php
namespace app\common\trait\config;

use app\common\enum\CustomComponent;
use app\common\builder\FormBuilder;
use Exception;

/**
 * 表单trait工具类
 * @author 贵州猿创科技有限公司
 * @copyright (c) 2023
 */
trait FormUtil
{
    /**
     * 应用ID（null则获取系统配置）
     * @var string|null
     */
    protected $saas_appid = null;

    /**
     * 扩展组件类型
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected $extraOptions = ['checkbox', 'radio', 'select'];

    /**
     * 获取表单规则
     * @param array $data
     * @param int $col
     * @return \app\common\builder\FormBuilder
     * @author John
     */
    private function getFormView(array $data,int $col = 24): FormBuilder
    {
        $builder   = new FormBuilder;
        # 获取自定义组件枚举列
        $custComponent = CustomComponent::getColumn('value');
        foreach ($data as $value) {
            # 数据验证
            hpValidate(\app\admin\validate\SystemConfig::class, $value);
            # 验证扩展组件
            if (in_array($value['component'], $this->extraOptions)) {
                if (empty($value['extra'])) {
                    throw new Exception("[{$value['title']}] - 扩展【extra】数据不能为空");
                }
                if (empty($value['extra']['options'])) {
                    throw new Exception("[{$value['title']}] - 扩展的【options】选项数据不能为空");
                }
            }
            #设置默认值
            $configValue = empty($value['value']) ? '' : $value['value'];
            # 设置扩展数据
            $configExtra = empty($value['extra']) ? [] : $value['extra'];
            # 设置组件参数
            if (in_array($value['component'], $custComponent)) {
                # 设置布局模式
                $configExtra['props']['col'] = $col;
                # 自定义组件
                $builder->addComponent(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $configExtra
                );
            } else {
                # 设置布局模式
                $configExtra['col'] = $col;
                # 普通组件
                $builder->addRow(
                    $value['name'],
                    $value['component'],
                    $value['title'],
                    $configValue,
                    $configExtra
                );
            }
        }
        # 获取表单句柄
        return $builder;
    }
}