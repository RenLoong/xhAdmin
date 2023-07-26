<?php
namespace app\common\manager;

use app\common\service\FrameworkService;
use app\common\service\SystemInfoService;
use app\common\manager\JsonMgr;
use app\common\utils\DirUtil;
use Exception;
use support\Request;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\PluginRequest;

/**
 * 应用更新管理器
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class PluginUpdateMgr
{
    /**
     * 请求对象
     * @var Request
     */
    protected $request = null;

    /**
     * 应用标识
     * @var string
     */
    protected $name = null;

    /**
     * 目标版本
     * @var int
     */
    protected $version = null;

    /**
     * 应用路径
     * @var string
     */
    protected $pluginPath = null;

    /**
     * 下载包储存路径
     * @var string
     */
    protected $zipFile = null;

    /**
     * 备份压缩包路径
     * @var string
     */
    protected $backPluginPath = null;

    /**
     * 构造函数
     * @param \support\Request $request
     * @param string $name
     * @param int $version
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function __construct(Request $request, string $name, int $version)
    {
        $this->request = $request;
        $this->name    = $name;
        $this->version = $version;
        # 应用目录
        $this->pluginPath = base_path("/plugin/{$this->name}");
        # 下载包储存路径
        $this->zipFile = runtime_path("/plugin/{$this->name}-install.zip");
        # 备份压缩包路径
        $this->backPluginPath = runtime_path("/plugin/{$this->name}-back.zip");
    }

    /**
     * 下载安装包
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function download()
    {
        try {
            # 已安装应用信息
            $installedVersion = PluginMgr::getPluginVersion($this->name);
            # 本地SAAS框架版本
            $systemInfo = SystemInfoService::info();
            # 获取下载KEY
            $req = new PluginRequest;
            $req->getKey();
            $req->name          = $this->name;
            $req->version       = $this->version;
            $req->saas_version  = $systemInfo['system_version'];
            $req->local_version = $installedVersion;
            $cloud              = new Cloud($req);
            $data               = $cloud->send();
            # 下载应用包
            $request = new \YcOpen\CloudService\Request();
            # 通过获取下载密钥接口获得
            $request->setUrl($data->url);
            # 保存文件到指定路径
            $request->setSaveFile($this->zipFile);
            $cloud  = new Cloud($request);
            $status = $cloud->send();
            if (!$status) {
                throw new Exception('安装包下载失败');
            }
            # 下载成功
            return JsonMgr::successFul('下载成功', [
                'next' => 'backCode'
            ]);
        } catch (\Throwable $e) {
            return JsonMgr::fail($e->getMessage());
        }
    }

    /**
     * 备份代码
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function backCode()
    {
        try {
            # 执行备份应用
            ZipMgr::build($this->name, $this->backPluginPath);
            # 备份成功
            return JsonMgr::successFul('备份成功', [
                'next' => 'backSql'
            ]);
        } catch (\Throwable $e) {
            return JsonMgr::fail($e->getMessage());
        }
    }

    /**
     * 备份数据库
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function backSql()
    {
        return JsonMgr::successFul('备份数据库', [
            'next' => 'delplugin',
        ]);
    }

    /**
     * 删除应用
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function delplugin()
    {
        # 删除应用
        DirUtil::delDir($this->pluginPath);
        # 删除成功
        return JsonMgr::successFul('删除应用', [
            'next' => 'unzip',
        ]);
    }

    /**
     * 解压安装包
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function unzip()
    {
        # 解压安装包
        ZipMgr::unzip($this->zipFile, $this->pluginPath);
        # 解压成功
        return JsonMgr::successFul('解压安装包', [
            'next' => 'updateData',
        ]);
    }

    /**
     * 安装数据
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function updateData()
    {
        try {
            # 获取安装类
            $installPath = base_path("/plugin/{$this->name}/api/Install.php");
            if (!file_exists($installPath)) {
                throw new Exception('安装类不存在');
            }
            # 引入安装类
            require_once $installPath;
            # 获取本地版本
            $localVersion = PluginMgr::getPluginVersion($this->name);
            # 更新数据上下文
            $context      = null;
            $installClass = "\\plugin\\{$this->name}\\api\\Install";
            # 执行beforeUpdate
            if (class_exists($installClass) && method_exists($installClass, 'beforeUpdate')) {
                $context = call_user_func([$installClass, 'beforeUpdate'], $localVersion, $this->version);
            }
            # 执行update更新
            if (class_exists($installClass) && method_exists($installClass, 'update')) {
                call_user_func([$installClass, 'update'], $localVersion, $this->version, $context);
            }
            # 删除安装包
            file_exists($this->zipFile) && unlink($this->zipFile);
            # 更新成功
            return JsonMgr::successFul('应用更新成功，请等待', [
                'next' => 'reload'
            ]);
        } catch (\Throwable $e) {
            # 执行回滚
            try {
                $this->rollback();
            } catch (\Throwable $e) {
                return JsonMgr::fail("更新失败，回滚失败：{$e->getMessage()}");
            }
            return JsonMgr::fail($e->getMessage());
        }
    }

    /**
     * 更新失败，回滚代码
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function rollback()
    {
        # 删除代码
        DirUtil::delDir($this->pluginPath);
        # 解压备份包
        ZipMgr::unzip($this->backPluginPath, $this->pluginPath);
        # 删除备份包
        file_exists($this->backPluginPath) && unlink($this->backPluginPath);
    }

    /**
     * 重启服务
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function reload()
    {
        # 重启主进程
        FrameworkService::reloadWebman();
        # 重启延迟
        sleep(3);
        # 重启成功
        return JsonMgr::successFul('重启服务',[
            'next'      => 'ping',
        ]);
    }

    /**
     * 检测服务是否重启成功
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function ping()
    {
        return JsonMgr::successFul('重启成功', [
            'next' => 'success',
        ]);
    }

    /**
     * 安装成功
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function success()
    {
        return JsonMgr::successFul('安装成功', [
            'next' => '',
        ]);
    }
}