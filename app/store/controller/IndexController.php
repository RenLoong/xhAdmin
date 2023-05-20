<?php

namespace app\store\controller;

use app\admin\service\kfcloud\SystemInfo;
use app\BaseController;
use app\enum\PlatformTypes;
use app\manager\StorePlatforms;
use app\store\model\Store;
use app\store\model\StoreApp;
use Exception;
use support\Request;

/**
 * 默认控制器
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
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
        $platformApp      = StorePlatforms::surplusNum((int) hp_admin_id('hp_store'));
        $platform_echarts = [];
        foreach ($platformTypes as $value) {
            // $where                        = [
            //     ['platform.platform_type', '=', $value['value']],
            // ];
            // // 查询图表数据
            // $today     = StoreApp::where($where)
            //     ->alias('app')
            //     ->join('store_platform platform', 'platform.id=app.platform_id')
            //     ->whereDay('app.create_at')->count();
            // $week      = StoreApp::where($where)
            //     ->alias('app')
            //     ->join('store_platform platform', 'platform.id=app.platform_id')
            //     ->whereWeek('app.create_at')->count();
            // $moon      = StoreApp::where($where)
            //     ->alias('app')
            //     ->join('store_platform platform', 'platform.id=app.platform_id')
            //     ->whereMonth('app.create_at')->count();
            // $quarter   = StoreApp::where($where)
            //     ->alias('app')
            //     ->join('store_platform platform', 'platform.id=app.platform_id')
            //     ->whereTime('app.create_at', '-3 month')->count();
            // $half_year = StoreApp::where($where)
            //     ->alias('app')
            //     ->join('store_platform platform', 'platform.id=app.platform_id')
            //     ->whereTime('app.create_at', '-6 month')->count();
            // $year      = StoreApp::where($where)
            //     ->alias('app')
            //     ->join('store_platform platform', 'platform.id=app.platform_id')
            //     ->whereYear('app.create_at')->count();
            // // 组装图表数据
            // $platform_echarts[] = [
            //     'name'   => $value['text'],
            //     'type'   => 'line',
            //     'stack'  => 'Total',
            //     'smooth' => true,
            //     'data'   => [
            //         $today,
            //         #今日
            //         $week,
            //         #本周
            //         $moon,
            //         #本月
            //         $quarter,
            //         #季度
            //         $half_year,
            //         #半年
            //         $year,
            //         #今年
            //     ],
            // ];
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
     * 清理服务端缓存
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function clear()
    {
        return parent::success('缓存清理成功');
    }

    /**
     * 解除UI锁定
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-03
     */
    public function lock()
    {
        return parent::success('解锁成功');
    }
}
