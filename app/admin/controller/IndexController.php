<?php

namespace app\admin\controller;

use app\admin\logic\AppCoreLogic;
use app\common\model\StoreApp;
use app\common\service\SystemInfoService;
use app\BaseController;
use app\common\enum\PlatformTypes;
use support\Request;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\SystemUpdateRequest;

/**
 * 首页数据
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-15
 */
class IndexController extends BaseController
{
    /**
     * 首页数据统计
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-12
     */
    public function consoleCount(Request $request)
    {
        // 获取系统信息
        $teamInfo = SystemInfoService::info();
        $inKeys = array_keys($teamInfo);
        $teamFields = [
            'about_name' => '研发企业',
            'ecology' => '系统生态',
            'fream_version' => '框架版本',
            'service_wx' => '微信咨询',
        ];
        $team = [];
        foreach ($teamFields as $field => $label) {
            if (in_array($field, $inKeys)) {
                $team[] = [
                    'title' => $label,
                    'values' => $teamInfo[$field],
                ];
            }
        }

        // 获取产品动态
        $product = [];

        // 应用总数量
        $platformTypes = PlatformTypes::getData();
        $platformApp = [];
        $platform_echarts = [];
        foreach ($platformTypes as $value) {
            $where = [
                ['platform.platform_type', '=', $value['value']],
            ];
            $count = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->count();
            $platformApp[$value['value']] = $count;

            // 查询图表数据
            $today = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereDay('app.create_at')
                ->count();
            $week = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereWeek('app.create_at')
                ->count();
            $moon = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereMonth('app.create_at')
                ->count();
            $quarter = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereTime('app.create_at', '-3 month')
                ->count();
            $half_year = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereTime('app.create_at', '-6 month')
                ->count();
            $year = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereYear('app.create_at')
                ->count();
            // 组装图表数据
            $platform_echarts[] = [
                'name' => $value['text'],
                'type' => 'line',
                'stack' => 'Total',
                'smooth' => true,
                'data' => [
                    $today,
                    #今日
                    $week,
                    #本周
                    $moon,
                    #本月
                    $quarter,
                    #季度
                    $half_year,
                    #半年
                    $year,
                    #今年
                ],
            ];
        }

        $data = [
            'team' => $team,
            'product' => $product,
            'platformApp' => $platformApp,
            'platform_echarts' => $platform_echarts
        ];
        return $this->successRes($data);
    }

    /**
     * 更新检测升级
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-15
     */
    public function updateCheck(Request $request)
    {
        $data = SystemInfo::info();
        if (!isset($data['system_version'])) {
            return $this->fail('获取本地版本错误');
        }

        $system_version = $data['system_version'];
        $version_name = $data['system_version_name'];
        $versionData = [
            'version_name' => '未知',
            'version' => 0,
            'client_version_name' => $version_name,
            'client_version' => $system_version,
        ];
        // 检测更新
        if ($request->method() === 'DELETE') {
            # 检测更新
            try {
                $req = new SystemUpdateRequest;
                $req->verify();
                $req->version_name = $version_name;
                $req->version = $system_version;
                $cloud = new Cloud($req);
                $data = $cloud->send();
                return $this->successRes($data->toArray());
            } catch (\Throwable $th) {
                return $this->json($th->getMessage(), 666, $versionData);
            }
        } else if ($request->method() === 'POST') {
            try {
                # 获取版本号
                $version = (int) $request->post('version');
                # 获取框架更新KEY
                $downKey = AppCoreLogic::getDownKey($version);
                # 下载更新包
                AppCoreLogic::downPack($downKey);
                # 开始更新框架
                AppCoreLogic::update($version);
                # 更新完成
                return $this->success('版本更新成功');
            } catch (\Throwable $e) {
                return $this->fail($e->getMessage());
            }
        } else {
            # 获取更新信息
            try {
                $req = new SystemUpdateRequest;
                $req->detail();
                $req->version_name = $version_name;
                $req->version = $system_version;
                $cloud = new Cloud($req);
                $data = $cloud->send();
                return $this->successRes($data->toArray());
            } catch (\Throwable $th) {
                return $this->json($th->getMessage(), 666, $versionData);
            }
        }
    }

    /**
     * 清理服务端缓存
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function clear(Request $request)
    {
        // 预留扩展（后续使用）
        return parent::success('缓存清理成功');
    }

    /**
     * 解除UI锁定
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function lock(Request $request)
    {
        // 预留扩展（后续使用）
        return parent::success('解锁成功');
    }
}