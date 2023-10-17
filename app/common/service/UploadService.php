<?php

namespace app\common\service;

use app\common\manager\StoreAppMgr;
use app\common\manager\UsersMgr;
use app\common\model\SystemUpload;
use app\common\model\SystemUploadCate;
use app\common\validate\AliyunValidate;
use app\common\validate\QcloudValidate;
use app\common\validate\QiniuValidate;
use Exception;
use think\facade\Config;
use think\file\UploadedFile;
use yzh52521\filesystem\facade\Filesystem;

/**
 * 附件服务类
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-04
 */
class UploadService
{
    /**
     * 下载远程文件并上传至附件库
     * @param string $url
     * @param string $dir_name
     * @param mixed $appid
     * @param mixed $uid
     * @param mixed $store_id
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function remoteFile(string $url, string $dir_name = '', $appid = null, $uid = null,$store_id = null): array
    {
        $fileInfo = pathinfo($url);
        if (!isset($fileInfo['extension'])) {
            throw new Exception('获取远程文件扩展失败');
        }
        if (!isset($fileInfo['filename'])) {
            throw new Exception('获取远程文件名称失败');
        }
        $fileMd5  = md5($fileInfo['filename']);
        # 储存临时缓存文件目录
        $tempFile = runtime_path() . "tempDown/{$fileMd5}.{$fileInfo['extension']}";
        if (!is_dir(dirname($tempFile))) {
            mkdir(dirname($tempFile), 0775, true);
        }
        # 读取远程文件
        $file = file_get_contents($url);
        if (!file_put_contents($tempFile, $file)) {
            throw new Exception('远程资源文件下载失败');
        }
        $fileMimeType = self::getFileMimeType($tempFile);
        $uploadFile   = new UploadedFile($tempFile, $fileInfo['basename'], $fileMimeType, 0);
        if (!$data = self::upload($uploadFile, $dir_name, $appid, $uid,$store_id)) {
            throw new Exception('上传文件失败');
        }
        return $data;
    }

    /**
     * 获取文件fileMime
     * @param mixed $file_path
     * @return bool|string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function getFileMimeType($file_path)
    {
        $finfo     = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_path);
        finfo_close($finfo);
        return $mime_type;
    }

    /**
     * 上传文件
     * @param \think\file\UploadedFile $file
     * @param string $dir_name 上传目录(分类dir_name)
     * @param mixed $appid 上传应用ID
     * @param mixed $uid 上传用户ID
     * @param mixed $store_id 上传渠道ID
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function upload(UploadedFile $file, string $dir_name = '', $appid = null, $uid = null,$store_id = null)
    {
        # 获取分类
        $category = self::getCategory($dir_name, $appid, $store_id, $uid);
        # 上传子目录
        $dirName = $category['dir_name'] ?? 'default';

        # 获取当前所有配置项
        $config = self::getCurrentConfig();
        # 获取当前使用驱动
        $uploadDrive = self::getDrive();

        # 获取驱动SDK
        $filesystem = self::getDisk();

        # 组装数据
        $data['cid']      = $category['id'];
        $data['title']    = $file->getOriginalName();
        $data['filename'] = $file->getOriginalName();
        $data['format']   = $file->extension();
        $data['adapter']  = $uploadDrive;
        $data['size']     = '';
        $data['path']     = '';
        $data['url']      = '';

        # 检测文件是否已上传
        $info = self::getFileInfo($data['filename'], $uploadDrive, $appid, $store_id);
        if (!empty($info)) {
            $data['size'] = $info['size'];
            $data['path'] = $info['path'];
            $data['url']  = $filesystem->url($info['path']);
            # 返回数据
            return $data;
        }

        # 上传文件
        $path = $filesystem->putFile($dirName, $file);

        # 本地附件库
        $localPath = $path;
        if ($uploadDrive === 'local') {
            $localPath = "{$config['root']}/{$path}";
        }
        # 根据用户重设应用ID
        if ($uid) {
            $user = UsersMgr::detail(['id'=> $uid]);
            $store_id = empty($user['store_id']) ? null : $user['store_id'];
            $appid = empty($user['saas_appid']) ? null : $user['saas_appid'];
        }
        # 根据项目ID重设渠道ID
        if ($appid) {
            $store = StoreAppMgr::detail(['id'=> $appid]);
            $store_id = empty($store['store_id']) ? null : $store['store_id'];
        }
        # 组装数据
        $data['cid']            = $category['id'];
        $data['saas_appid']     = $appid;
        $data['store_id']       = $store_id;
        $data['title']          = $file->getOriginalName();
        $data['filename']       = $file->getOriginalName();
        $data['path']           = $localPath;
        $data['format']         = $file->extension();
        $data['size']           = get_size($filesystem->size($path));
        $data['adapter']        = $uploadDrive;
        $data['url']            = $filesystem->url($localPath);

        $model = new SystemUpload;
        if (!$model->save($data)) {
            throw new Exception('附件上传失败');
        }
        # 返回数据
        return $data;
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
        $disk = self::getDisk();
        $url  = $disk->url($path);
        return $url;
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
        if (is_array($url)) {
            $data = [];
            if (count($url) === 1) {
                return self::path(current($url));
            }
            foreach ($url as $value) {
                if (filter_var($value, FILTER_SANITIZE_URL) === false) {
                    throw new Exception('URL地址不合法');
                }
                $parseUrl = parse_url($value);
                $data[]   = ltrim($parseUrl['path'], '/');
            }
            return $data;
        } else {
            if (filter_var($url, FILTER_SANITIZE_URL) === false) {
                throw new Exception('URL地址不合法');
            }
            $parseUrl = parse_url($url);
            $data     = ltrim($parseUrl['path'], '/');
            return $data;
        }
    }

    /**
     * 检测文件是否已存在
     * @param mixed $fileName
     * @param mixed $adapter
     * @param mixed $appid
     * @param mixed $store_id
     * @return mixed
     * @author John
     */
    public static function getFileInfo($fileName, $adapter, $appid = null, $store_id = null)
    {
        $where['filename'] = $fileName;
        $where['adapter']  = $adapter;
        if ($appid) {
            $where['saas_appid'] = $appid;
        }
        if ($store_id) {
            $where['store_id'] = $store_id;
        }
        $fileModel = SystemUpload::where($where)->find();
        if ($fileModel) {
            $fileModel->update_at = date('Y-m-d H:i:s');
            $fileModel->save();
            return $fileModel->toArray();
        }
        return [];
    }

    /**
     * 获取储存的分类
     * @param string $dir_name
     * @param mixed $appid
     * @param mixed $store_id
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function getCategory(string $dir_name, $appid = null, $store_id = null, $uid = null): array
    {
        $where = [];
        if ($dir_name) {
            $where[] = ['dir_name', '=', $dir_name];
        } else {
            $where[] = ['is_system', '=', '20'];
        }
        if ($appid) {
            $where[] = ['saas_appid', '=', $appid];            
        }
        if ($store_id) {
            $where[] = ['store_id', '=', $store_id];
        }
        if ($uid) {
            $where[] = ['uid', '=', $uid];
        }
        $category = SystemUploadCate::where($where)->find();
        if (!$category) {
            $category = SystemUploadCate::order(['id' => 'asc'])->find();
        }
        if (!$category) {
            throw new Exception('没有更多的附件分类可用');
        }
        return $category->toArray();
    }

    /**
     * 获取附件系统配置项
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getConfig()
    {
        return getHpConfig('', null, 'upload');
    }

    /**
     * 获取当前使用配置项
     * @return mixed
     * @author John
     */
    public static function getCurrentConfig()
    {
        $config = self::getConfig();
        if (empty($config['upload_drive'])) {
            throw new Exception('请先设置附件驱动');
        }
        # 当前使用驱动
        $drive = $config['upload_drive'];
        $settings = isset($config['children'][$drive]) ? $config['children'][$drive] : [];
        if (empty($settings) && !isset($settings['children'])) {
            $settings = $config;
        }
        return $settings;
    }

    /**
     * 获取当前使用驱动
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getDrive()
    {
        $config = self::getConfig();
        return $config['upload_drive'] ?? '';
    }

    /**
     * 获取驱动SDK
     * @return \yzh52521\filesystem\Driver
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getDisk()
    {
        # 获取配置项
        $config = self::getConfig();
        if (empty($config['upload_drive'])) {
            throw new Exception('请先设置附件驱动');
        }
        # 当前使用驱动
        $drive = $config['upload_drive'];
        $settings = isset($config['children'][$drive]) ? $config['children'][$drive] : [];
        # 删除驱动配置项
        unset($config['upload_drive']);
        if (empty($settings)) {
            $settings = $config;
        }
        if (empty($settings)) {
            throw new Exception('请先设置附件库');
        }
        # 阿里云驱动验证
        if ($drive === 'aliyun') {
            hpValidate(AliyunValidate::class, $settings);
        }
        # 腾讯云驱动
        if ($drive === 'qcloud') {
            hpValidate(QcloudValidate::class, $settings);
        }
        # 七牛云驱动
        if ($drive === 'qiniu') {
            hpValidate(QiniuValidate::class, $settings);
        }

        # 合并配置
        $settings = array_merge(config("filesystem.disks.{$drive}", []), $settings);

        # 动态设置配置
        Config::set([
            'default'       => $drive,
            'disks'         => [
                $drive      => $settings,
            ]
        ], 'filesystem');

        # 获取驱动SDK
        return Filesystem::disk($drive);
    }
}