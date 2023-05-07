<?php

namespace app\service;

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
     * @param mixed $file
     * @param int $cid
     * @param array $config TODO:该参数预留后续使用
     * @throws Exception
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-05
     */
    public static function upload($file, int $cid, array $config = []): bool|string
    {
        try {
            $category = self::getCategory($cid);
            $path     = "upload/{$category['dir_name']}";
            $result   = (new Storage)->path($path)->upload($file, false);
            if (!self::addUpload($result, $category)) {
                throw new Exception('文件保存失败');
            }
            return $path;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * 删除系统附件
     * @param int $id
     * @param mixed $force
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public static function delete(int $id, $force = false): bool
    {
        return SystemUpload::destroy($id, $force);
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
        return (string) (new Storage)->url($path);
    }

    /**
     * 批量获取URL外链
     * @param array $data
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function urls(array $array)
    {
        $data = [];
        foreach ($array as $value) {
            $data[] = self::url($value);
        }
        return $data;
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
     * @param string|array $url
     * @return array|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public static function path(string|array $url)
    {
        $config    = config('plugin.shopwwi.filesystem.app');
        $default   = $config['default'] ?? 'public';
        $configUrl = isset($config['storage'][$default]['url']) ? "{$config['storage'][$default]['url']}/" : '';
        if (is_array($url)) {
            $data = [];
            foreach ($url as $value) {
                $data[] = str_replace($configUrl, '', $value);
            }
            return count($data) === 1 ? current($data) : $data;
        }
        else {
            return str_replace($configUrl, '', $url);
        }
    }

    /**
     * 附件储存
     * @param mixed $result
     * @param array $category
     * @return bool
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    private static function addUpload($result, array $category): bool
    {
        $appid             = request()->header('appid');
        $fiel_name         = basename($result->file_name);
        $where['filename'] = $fiel_name;
        $fileModel         = SystemUpload::where($where)->count();
        if ($fileModel) {
            return true;
        }
        $data['cid']      = $category['id'];
        $data['appid']    = $appid;
        $data['title']    = $result->origin_name;
        $data['filename'] = basename($result->file_name);
        $data['path']     = $result->file_name;
        $data['format']   = $result->extension;
        $data['size']     = $result->size;
        $data['adapter']  = $result->adapter;
        return (new SystemUpload)->save($data);
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
            $where[] = ['id', '=', $cid];
        }
        else {
            $where[] = ['is_system', '=', '1'];
        }
        $category = SystemUploadCate::where($where)->find();
        if (!$category) {
            throw new Exception('获取附件分类错误');
        }
        return $category->toArray();
    }
}