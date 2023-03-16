<?php

namespace app;

use app\enum\UploadifyType;
use app\service\Upload;
use think\Model as ThinkModel;

/**
 * @title 数据库基类
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Model extends ThinkModel
{
    // 开启自动时间戳
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    /**
     * 查询后事件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  type $model
     * @return void
     */
    public static function onAfterRead($model)
    {
        $uploadify = $model->uploadify ?? [];
        foreach ($uploadify as $field => $fieldValue) {
            // 多文件
            if (in_array($fieldValue, UploadifyType::getEnumValues()) && $model->$field) {
                $paths = explode(',', $model->$field);
                foreach ($paths as $key => $value) {
                    $paths[$key] = Upload::model($value);
                }
                $model->$field = $paths;
            }
        }
    }

    /**
     * 新增前置事件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  type $model
     * @return void
     */
    public static function onBeforeInsert($model)
    {
        $uploadify = $model->uploadify ?? [];
        $post = request()->post();
        foreach ($uploadify as $field => $fieldValue) {
            // 更新空文件
            if (!isset($post[$field]) || !$post[$field]) {
                $model->$field = '';
            }
            // 附件写入
            if (in_array($fieldValue, UploadifyType::getEnumValues()) && isset($post[$field])) {
                $paths = $post[$field];
                foreach ($paths as $key => $value) {
                    $paths[$key] = Upload::path($value);
                }
                $model->$field = implode(',', $paths);
            }
        }
    }

    /**
     * 更新前置事件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  type $model
     * @return void
     */
    public static function onBeforeUpdate($model)
    {
        $uploadify = $model->uploadify ?? [];
        $post = request()->post();
        foreach ($uploadify as $field => $fieldValue) {
            // 更新空文件
            if (!isset($post[$field]) || !$post[$field]) {
                $model->$field = '';
            }
            // 附件更新
            if (in_array($fieldValue, UploadifyType::getEnumValues()) && isset($post[$field])) {
                $paths = $post[$field];
                foreach ($paths as $key => $value) {
                    $paths[$key] = Upload::path($value);
                }
                $model->$field = implode(',', $paths);
            }
        }
    }

    /**
     * 删除前置事件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-11
     * @param  type $model
     * @return void
     */
    public static function onBeforeDelete($model)
    {
        // TODO:检测如果是附件类型字段，则删除远程文件
    }
}
