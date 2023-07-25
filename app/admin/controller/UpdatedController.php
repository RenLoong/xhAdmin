<?php

namespace app\admin\controller;

use app\common\exception\RollBackException;
use app\common\service\SystemRollbackService;
use app\common\service\SystemUpdateService;
use app\common\service\SystemInfoService;
use app\BaseController;
use Exception;
use process\Monitor;
use support\Request;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\SystemUpdateRequest;

/**
 * 更新检测升级
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 * @email 416716328@qq.com
 */
class UpdatedController extends BaseController
{
    /**
     * 版本信息
     * @var array
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $versionData = [];

    /**
     * 系统版本
     * @var int
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $system_version = 0;

    /**
     * 系统版本名称
     * @var string
     * @author 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private $version_name = '';

    /**
     * 构造函数
     * @throws \Exception
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct()
    {
        $data = SystemInfoService::info();
        if (!isset($data['system_version'])) {
            throw new Exception('获取本地版本错误');
        }
        $this->system_version = $data['system_version'];
        $this->version_name   = $data['system_version_name'];
        $this->versionData    = [
            'version_name' => '未知',
            'version' => 0,
            'client_version_name' => $this->version_name,
            'client_version' => $this->system_version,
        ];
        parent::__construct();
    }
    /**
     * 更新升级
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function updateCheck(Request $request)
    {
        switch ($request->method()) {
            # 更新版本
            case 'POST':
                $response = $this->checkUpdate($request);
                break;
            # 检测更新
            case 'DELETE':
                $response = $this->verifyUpdate($request);
                break;
            # 获取更新版本信息
            default:
                $response = $this->getUpdateInfo($request);
                break;
        }
        return $response;
    }

    /**
     * 处理更新
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    private function checkUpdate(Request $request)
    {
        $funcName = $request->get('step', '');
        $version  = (int) $request->get('version', 0);
        if (empty($version)) {
            return $this->fail('更新目标版本参数错误');
        }
        if (empty($funcName)) {
            return $this->fail('操作方法出错');
        }
        # 暂停代码监听变动
        $monitor_support_pause = method_exists(Monitor::class, 'pause');
        if ($monitor_support_pause) {
            Monitor::pause();
        }
        try {
            $class = new SystemUpdateService($request);
            return call_user_func([$class, $funcName]);
        } catch (RollBackException $e) {
            # 开始进行版本回滚
            (new SystemRollbackService($request))->startRollback();
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        } finally {
            # 恢复代码监听变动
            if ($monitor_support_pause) {
                Monitor::resume();
            }
        }
    }

    /**
     * 验证是否更新
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function verifyUpdate(Request $request)
    {
        try {
            $req = new SystemUpdateRequest;
            $req->verify();
            $req->version_name = $this->version_name;
            $req->version      = $this->system_version;
            $cloud             = new Cloud($req);
            $data              = $cloud->send();
            return $this->successRes($data->toArray());
        } catch (\Throwable $th) {
            return $this->json($th->getMessage(), 666, $this->versionData);
        }
    }

    /**
     * 获取更新信息
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function getUpdateInfo(Request $request)
    {
        # 获取更新信息
        try {
            $req = new SystemUpdateRequest;
            $req->detail();
            $req->version_name = $this->version_name;
            $req->version      = $this->system_version;
            $cloud             = new Cloud($req);
            $data              = $cloud->send();
            return $this->successRes($data->toArray());
        } catch (\Throwable $th) {
            return $this->json($th->getMessage(), 666, $this->versionData);
        }
    }
}