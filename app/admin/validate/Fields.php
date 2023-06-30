<?php

namespace app\admin\validate;

use yzh52521\validate\Validate;

class Fields extends Validate
{
    protected $rule =   [
        'name'                      => 'require',
        'comment'                   => 'require',
        'type'                      => 'require',
        'length'                    => 'verifyLength',
        'default'                   => 'verifyDefault',
    ];

    protected $message  =   [
        'name.require'              => '请输入字段名称',
        'comment.require'           => '请输入字段注释',
        'type.require'              => '请选择字段类型',
    ];

    /**
     * 验证长度
     * @param mixed $value
     * @param mixed $rule
     * @param mixed $data
     * @return bool|string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function verifyLength($value,$rule,$data)
    {
        if (in_array($value,['int','string','char','float','decimal','double','enum'])) {
            if (in_array($data['type'],['enum'])) {
                # 枚举类型
                if (stripos($value,',') === false) {
                    return '枚举类型必须以小写逗号隔开';
                }
            }else{
                # 数字类型
                $value = intval($value);
                if ($value > 11 && $data['type'] === 'int') {
                    return '数字类型最大11位';
                }
                if (in_array($data['type'],['float','decimal','double']) && $value > 10) {
                    return '浮点类型最大10位';
                }
                if ($value > 255 && in_array($data['type'],['string','char'])) {
                    return '字符串类型最大255位';
                }
            }
        }
        return true;
    }

    /**
     * 验证默认值
     * @param mixed $value
     * @param mixed $rule
     * @param mixed $data
     * @return bool|string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function verifyDefault($value,$rule,$data)
    {
        if (in_array($data['type'],['string','char','enum'])) {
            return '请输入数据长度';
        }
        return true;
    }
}
