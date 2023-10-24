<?php

namespace app\common\service\upload;

use app\common\manager\SettingsMgr;
use app\common\model\SystemUploadCate;
use app\common\validate\AliyunValidate;
use app\common\validate\QcloudValidate;
use app\common\validate\QiniuValidate;
use app\store\model\SystemUpload;
use Exception;
use think\facade\Config;
use yzh52521\filesystem\facade\Filesystem;

/**
 * 附件上传基础类
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
trait BaseUpload
{
    /**
     * 渠道ID
     * @var null|int
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected static $store_id = null;

    /**
     * 应用ID
     * @var null|int
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected static $saas_appid = null;

    /**
     * 用户ID
     * @var null|int
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected static $uid = null;

    /**
     * 检测文件是否已存在
     * @param mixed $fileName
     * @param mixed $adapter
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getFileInfo($fileName, $adapter)
    {
        $where['filename'] = $fileName;
        $where['adapter']  = $adapter;
        if (self::$saas_appid) {
            $where['saas_appid'] = self::$saas_appid;
        }
        if (self::$store_id) {
            $where['store_id'] = self::$store_id;
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
    protected static function getCategory(string $dir_name): array
    {
        $where = [];
        if ($dir_name) {
            $where[] = ['dir_name', '=', $dir_name];
        } else {
            $where[] = ['is_system', '=', '20'];
        }
        if (self::$saas_appid) {
            $where[] = ['saas_appid', '=', self::$saas_appid];            
        }
        if (self::$store_id) {
            $where[] = ['store_id', '=', self::$store_id];
        }
        if (self::$uid) {
            $where[] = ['uid', '=', self::$uid];
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
     * 获取附件库配置项
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getConfig()
    {
        if (self::$saas_appid) {
            # 应用级附件库
            $config = SettingsMgr::group(self::$saas_appid, 'upload',[]);
        } else {
            # 系统级附件库
            $config = SettingsMgr::group(null, 'upload',[]);
        }
        if (empty($config['upload_drive'])) {
            throw new Exception('附件库驱动未设置',13000);
        }
        if (empty($config['children'])) {
            throw new Exception('请先设置附件库',13000);
        }
        return $config;
    }

    /**
     * 获取当前使用配置项
     * @throws \Exception
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getCurrentConfig()
    {
        $config = self::getConfig();
        # 当前使用驱动
        $drive = $config['upload_drive'];
        # 附件库配置
        $settings = isset($config['children'][$drive]) ? $config['children'][$drive] : [];
        if (empty($config['children'])) {
            throw new Exception('请先设置附件库上传设置',13000);
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
     * @param mixed $drive
     * @return \yzh52521\filesystem\Driver
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getDisk($drive = '')
    {
        # 获取配置项
        $config = self::getConfig();
        # 设置驱动
        if (empty($drive)) {
            $drive = $config['upload_drive'];
        }
        # 当前使用附件库配置
        $settings = [];
        # 获取全部配置项
        $configOptions = $config['children'];
        # 合并配置
        $templateConfig = config("filesystem.disks", []);
        foreach ($configOptions as $key => $value) {
            $configSave = array_merge($templateConfig[$key] ?? [], $value);
            $settings[$key] = $configSave;
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
        # 动态设置配置
        Config::set([
            'default'       => $drive,
            'disks'         => $settings
        ], 'filesystem');
        # 获取驱动SDK
        return Filesystem::disk($drive);
    }

    /**
     * 设置渠道ID
     * @param null|int $id
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function setStoreId(null|int $id)
    {
        self::$store_id = $id;
    }

    /**
     * 设置应用ID
     * @param null|int $id
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function setSaasAppid(null|int $id)
    {
        self::$saas_appid = $id;
    }

    /**
     * 设置用户ID
     * @param null|int $id
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function setUid(null|int $id)
    {
        self::$uid = $id;
    }

    /**
     * 获取驱动SDK（即将废弃）
     * @return \yzh52521\filesystem\Driver
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function getDisk1($drive = '')
    {
        # 获取配置项
        $config = self::getConfig();
        if (empty($config['upload_drive'])) {
            throw new Exception('请先设置附件驱动');
        }
        # 设置参数驱动
        if ($drive) {
            $config['upload_drive'] = $drive;
        }
        # 验证是否旧版本驱动
        $oldDrive = ['oss'=>'aliyun','cos'=> 'qcloud'];
        if (isset($oldDrive[$config['upload_drive']])) {
            $drive = $oldDrive[$config['upload_drive']];
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