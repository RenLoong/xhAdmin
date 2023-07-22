<?php
namespace app\common\service;
use app\common\manager\ZipMgr;
use app\common\manager\JsonMgr;
use app\common\utils\DirUtil;
use Exception;
use support\Request;
use YcOpen\CloudService\Request as CloudRequest;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\SystemUpdateRequest;

/**
 * 更新服务类
 * 更新步骤如下：
 * 1、下载更新包
 * 2、备份代码
 * 3、备份数据库
 * 4、删除代码
 * 5、解压更新包
 * 6、执行数据同步更新
 * 7、重启主进程服务
 * 8、等待服务重启
 * 9、更新成功
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class SystemUpdateService
{
    /**
     * 当前请求管理器
     * @var Request
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $request = null;

    /**
     * 本地版本名称
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $clientVersionName = '';

    /**
     * 本地版本号
     * @var int
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $clientVersion = 0;

    /**
     * 临时ZIP文件路径
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $tempZipFilePath = null;

    /**
     * 备份源代码路径
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $backupPath = null;

     /**
      * 备份覆盖代码路径
      * @var 
      * @author 贵州猿创科技有限公司
      * @email 416716328@qq.com
      */

    private $backCoverPath = null;

    /**
     * 解压的目标路径
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $targetPath = null;

    
    /**
     * 打包时忽略文件或目录列表
     * 删除时忽略以下目录或文件
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $ignoreList = [
        'public/upload',
        'plugin',
        'runtime',
    ];

    /**
     * 备份需要覆盖的文件
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $backCoverList = [
        '.env',
        'config/plugin',
        'config/redis.php',
    ];

    /**
     * 构造函数
     * @param \support\Request $Request
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct(Request $Request)
    {
        # 设置请求实体
        $this->request = $Request;
        # 下载框架更新包临时地址
        $this->tempZipFilePath = runtime_path("/core/kfadmin.zip");
        # 解压至目标地址
        // $this->targetPath = base_path();
        $this->targetPath = runtime_path('temp');
        # 备份当前版本代码地址值
        $this->backupPath = runtime_path("/core/backup.zip");
        # 备份覆盖代码地址值
        $this->backCoverPath = runtime_path("/core/cover.zip");
        # 本地版本信息
        $clientSystemVersion     = SystemInfoService::info();
        $this->clientVersion = $clientSystemVersion['system_version'];
        $this->clientVersionName = $clientSystemVersion['system_version_name'];
    }

    /**
     * 下载更新包
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function download()
    {
        # 获取更新目标版本
        $version = (int) $this->request->get('version', 0);
        if (!$version) {
            throw new Exception('更新目标版本参数错误');
        }
        # 获取更新包下载地址
        $req = new SystemUpdateRequest;
        $req->getKey();
        $req->target_version = $version;
        $cloud = new Cloud($req);
        $data = $cloud->send();
        $downUrl = $data->url;
        # 下载更新包
        $req = new CloudRequest;
        $req->setUrl($downUrl);
        $req->setSaveFile($this->tempZipFilePath);
        $cloud = new Cloud($req);
        $cloud->send();
        # 下载成功
        return JsonMgr::successRes([
            'next' => 'backCode'
        ]);
    }

    /**
     * 备份代码
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function backCode()
    {
        # 打包至目标压缩包
        if (!is_dir(dirname($this->backupPath))) {
            mkdir(dirname($this->backupPath), 0755, true);
        }
        # 备份原始代码
        ZipMgr::build($this->backupPath, base_path(), $this->ignoreList);
        # 备份覆盖代码
        ZipMgr::buildFiles($this->backCoverPath, base_path(), $this->backCoverList);
        # 打包成功
        return JsonMgr::successRes([
            'next' => 'backSql'
        ]);
    }

    /**
     * 备份数据库
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function backSql()
    {
        return JsonMgr::successRes([
            'next' => 'delCode'
        ]);
    }

    /**
     * 删除代码
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function delCode()
    {
        # 检测目标路径不存在则继续下一步
        if (!is_dir($this->targetPath)) {
            return JsonMgr::successRes([
                'next' => 'unzip'
            ]);
        }
        $targetPath       = $this->targetPath;
        # 判断是否为斜杠结尾
        if (substr($targetPath, -1) != '/') {
            $targetPath .= '/';
        }
        # 组装需要忽略的文件路径
        $ignore = [];
        foreach ($this->ignoreList as $v) {
            $ignore[] = $targetPath.$v;
        }
        # 删除原始代码
        DirUtil::delDir($this->targetPath, $ignore);
        # 删除成功
        return JsonMgr::successRes([
            'next' => 'unzip'
        ]);
    }

    /**
     * 解压更新包
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function unzip()
    {
        # 检测目标路径不存在则创建
        if (!is_dir($this->targetPath)) {
            mkdir($this->targetPath, 0755, true);
        }
        # 解压更新包
        ZipMgr::unzip($this->tempZipFilePath, $this->targetPath);
        # 解压覆盖文件
        ZipMgr::unzip($this->backCoverPath, $this->targetPath);
        # 解压成功，删除临时文件
        unlink($this->tempZipFilePath);
        # 返回成功
        return JsonMgr::successRes([
            'next' => 'updateData'
        ]);
    }

    /**
     * 执行数据同步更新
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function updateData()
    {
        # 更新服务类
        $class = "app\\common\\service\\UpdateService";
        if (class_exists($class)) {
            # 执行更新前置
            $context       = [];
            if (method_exists($class, 'beforeUpdate')) {
                $context = call_user_func(
                    [$class, 'beforeUpdate'],
                    $this->clientVersion,
                    $this->request
                );
            }
            # 执行update更新
            if (method_exists($class, 'update')) {
                call_user_func(
                    [$class, 'update'],
                    $this->clientVersion,
                    $context
                );
            }
        }
        # 更新成功
        return JsonMgr::successRes([
            'next' => 'reload'
        ]);
    }

    /**
     * 重启主进程服务
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function reload()
    {
        # 重启主进程服务
        FrameworkService::reloadWebman();
        # 延迟3秒，等待服务重启
        sleep(3);
        # 重启成功
        return JsonMgr::successRes([
            'next' => 'ping'
        ]);
    }

    /**
     * 等待服务重启
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function ping()
    {
        return JsonMgr::successFul('更新成功，即将跳转首页...', [
            'next' => 'success'
        ]);
    }
}