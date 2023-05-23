<?php

namespace app\admin\controller;

use app\admin\logic\PluginLogic;
use app\admin\model\StoreApp;
use app\admin\service\kfcloud\SystemInfo;
use app\admin\service\kfcloud\Updated;
use app\BaseController;
use app\enum\PlatformTypes;
use app\utils\Utils;
use process\Monitor;
use support\Request;
use ZipArchive;

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
        $teamInfo   = SystemInfo::info();
        $inKeys     = array_keys($teamInfo);
        $teamFields = [
            'about_name'    => '研发企业',
            'ecology'       => '系统生态',
            'fream_version' => '框架版本',
            'service_wx'    => '微信咨询',
        ];
        $team       = [];
        foreach ($teamFields as $field => $label) {
            if (in_array($field, $inKeys)) {
                $team[] = [
                    'title'  => $label,
                    'values' => $teamInfo[$field],
                ];
            }
        }

        // 获取产品动态
        $product = [];

        // 应用总数量
        $platformTypes    = PlatformTypes::getData();
        $platformApp      = [];
        $platform_echarts = [];
        foreach ($platformTypes as $value) {
            $where                        = [
                ['platform.platform_type', '=', $value['value']],
            ];
            $count                        = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->count();
            $platformApp[$value['value']] = $count;

            // 查询图表数据
            $today     = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereDay('app.create_at')->count();
            $week      = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereWeek('app.create_at')->count();
            $moon      = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereMonth('app.create_at')->count();
            $quarter   = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereTime('app.create_at', '-3 month')->count();
            $half_year = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereTime('app.create_at', '-6 month')->count();
            $year      = StoreApp::where($where)
                ->alias('app')
                ->join('store_platform platform', 'platform.id=app.platform_id')
                ->whereYear('app.create_at')->count();
            // 组装图表数据
            $platform_echarts[] = [
                'name'   => $value['text'],
                'type'   => 'line',
                'stack'  => 'Total',
                'smooth' => true,
                'data'   => [
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
            'team'             => $team,
            'product'          => $product,
            'platformApp'      => $platformApp,
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
        $version_name   = $data['system_version_name'];
        // 检测更新
        if ($request->method() === 'DELETE') {
            $data = Updated::verify($system_version, $version_name)->array();
            if (!$data) {
                return $this->failFul('没有获取到更新信息',666);
            }
            if (!isset($data['data'])) {
                return $this->fail('获取版本包体错误');
            }
            return json($data);
        }
        else if ($request->method() === 'POST') {
            $version = (int)$request->post('version');
            $response     = Updated::getSystemDownKey($version)->array();
            if (!$response) {
                return $this->fail('获取更新KEY失败');
            }
            if(!isset($response['code']) && !isset($response['data'])){
                return $this->fail('请求更新失败');
            }
            if ($response['code'] !== 200) {
                return json($response);
            }
            $key = $response['data']['key'];
            $packRes = Updated::getZip($key);
            $pack    = $packRes->array();
            if (is_array($pack) && isset($pack['code'])) {
                return json($pack);
            }
            // 写入包体
            $zip_file = runtime_path('/updated.zip');
            file_put_contents($zip_file, $packRes->body());
            // 效验系统函数
            $has_zip_archive = class_exists(ZipArchive::class, false);
            if (!$has_zip_archive) {
                $cmd = PluginLogic::getUnzipCmd($zip_file, base_path());
                if (!$cmd) {
                    return $this->fail('请给php安装zip模块或者给系统安装unzip命令');
                }
                if (!function_exists('proc_open')) {
                    return $this->fail('请解除proc_open函数的禁用或者给php安装zip模块');
                }
            }
            $monitor_support_pause = method_exists(Monitor::class, 'pause');
            if ($monitor_support_pause) {
                Monitor::pause();
            }
            try {
                // 解压zip到根目录
                if ($has_zip_archive) {
                    $zip = new ZipArchive;
                    $zip->open($zip_file, ZIPARCHIVE::CHECKCONS);
                }

                $install_class = "\\app\\Install";
                if (!empty($zip)) {
                    $zip->extractTo(base_path());
                    echo "框架更新成功...\n";
                    unset($zip);
                }
                else {
                    PluginLogic::unzipWithCmd($cmd);
                }
                unlink($zip_file);
                // 执行install安装
                if (class_exists($install_class) && method_exists($install_class, 'install')) {
                    call_user_func([$install_class, 'install'], $version);
                    echo "执行更新安装成功...\n";
                    unlink($install_class);
                }
            } finally {
                if ($monitor_support_pause) {
                    Monitor::resume();
                }
            }
            // 重启框架
            Utils::reloadWebman();
            echo "更新重启成功...\n";

            // 执行返回
            return $this->success('更新成功');
        }
        else {
            // 获取更新信息
            $data = Updated::systemUpdateInfo($system_version, $version_name)->array();
            if (!$data) {
                return $this->failFul('没有获取到更新信息',666);
            }
            return json($data);
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