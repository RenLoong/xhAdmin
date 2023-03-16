<?php

namespace app\service;

use app\admin\model\SystemAdmin;
use app\admin\model\SystemUpload;
use app\admin\model\SystemUploadCate;
use Exception;
use Shopwwi\WebmanFilesystem\Storage;
use support\Log;

/**
 * 附件服务类
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-04
 */
class Upload
{
    /**
     * 上传文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  type    $file
     * @param  array   $config TODO:该参数预留后续使用
     * @param  integer $cid
     * @return boolean
     */
    public static function upload($file, array $config = [], int $cid = 0): bool
    {
        try {
            $category = self::getCategory($cid);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
        $path = "upload/{$category['dir_name']}";
        try {
            $result = (new Storage)
                ->path($path)
                ->upload($file, false);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
        return self::addUpload($result, $category);
    }

    /**
     * 删除系统附件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-05
     * @param  array   $ids
     * @return boolean
     */
    public static function delete(array $ids): bool
    {
        return SystemUpload::destroy($ids);
    }

    /**
     * 获取外链地址
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  string $path
     * @return string
     */
    public static function url(string $path): string
    {
        return (string)(new Storage)->url($path);
    }

    /**
     * 查询模型数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  string $path
     * @return array
     */
    public static function model(string $path): array|string
    {
        $where = [
            ['path', '=', $path],
        ];
        $field = [
            'path',
            'format',
            'size',
        ];
        $model = SystemUpload::where($where)->field($field)->find();
        if (!$model) {
            return '';
        }
        return $model->toArray();
    }

    /**
     * URL转PATH
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-09
     * @param  string $url
     * @return string
     */
    public static function path(string $url): string
    {
        $config = config('plugin.shopwwi.filesystem.app');
        $default = $config['default'] ?? 'public';
        $configUrl = isset($config['storage'][$default]['url']) ? "{$config['storage'][$default]['url']}/" : '';
        return str_replace($configUrl, '', $url);
    }

    /**
     * 附件储存
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @param  type    $resutl
     * @return boolean
     */
    private static function addUpload($result, array $category): bool
    {
        $fiel_name = basename($result->file_name);
        $where['filename'] = $fiel_name;
        $fileModel = SystemUpload::where($where)->count();
        if ($fileModel) {
            return true;
        }
        $data['cid'] = $category['id'];
        $data['title'] = $result->origin_name;
        $data['filename'] = basename($result->file_name);
        $data['path'] = $result->file_name;
        $data['format'] = $result->extension;
        $data['size'] = $result->size;
        $data['adapter'] = $result->adapter;
        return (new SystemUpload($data))->save();
    }

    /**
     * 获取储存的分类
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-04
     * @return array
     */
    private static function getCategory(int $cid): array
    {
        if ($cid) {
            $where[] = ['cid', '=', $cid];
        } else {
            $where[] = ['is_system', '=', '1'];
        }
        $category = SystemUploadCate::where($where)->find();
        if (!$category) {
            throw new Exception('获取附件分类错误');
        }
        return $category->toArray();
    }
}
