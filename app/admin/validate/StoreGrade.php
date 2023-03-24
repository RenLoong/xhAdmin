<?php

namespace app\admin\validate;

use app\admin\model\Store as ModelStore;
use think\Validate;

class StoreGrade extends Validate
{
    protected $rule =   [
        'title'             => 'require|verifyTitle',
        'sort'              => 'require',
    ];

    protected $message  =   [
        'title.require'     => '请输入等级名称',
        'sort.require'      => '请输入等级排序',
    ];


    /**
     * 添加场景验证
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @return void
     */
    public function sceneAdd()
    {
        return $this
            ->only([
                'title',
                'sort',
            ]);
    }

    /**
     * 修改场景
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @return void
     */
    public function sceneEdit()
    {
        return $this
            ->only([
                'title',
                'sort',
            ])
            ->remove('title', ['verifyTitle']);
    }

    /**
     * 验证名称
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-12
     * @param  type $value
     * @return void
     */
    protected function verifyTitle($value)
    {
        $where = [
            'title'  => $value
        ];
        if (ModelStore::where($where)->count()) {
            return '该等级名称已存在';
        }
        return true;
    }
}
