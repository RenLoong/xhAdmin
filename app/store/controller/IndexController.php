<?php

namespace app\store\controller;

use app\BaseController;
use app\enum\PlatformTypes;
use app\manager\StorePlatforms;
use app\store\model\Store;
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
        # 获取系统信息
        $store_id = hp_admin_id('hp_store');
        $store    = Store::where(['id'=> $store_id])->find()->toArray();
        # 设置系统默认版权
        $copyright_name    = getHpConfig('store_copyright_name');
        $copyright_service = getHpConfig('store_copyright_service');
        $tutorial          = $this->getTutorial((string) getHpConfig('store_copyright_tutorial'));

        # 租户已设置版权名称
        if ($store['title']) {
            $copyright_name = $store['title'];
        }
        if ($store['copyright_service']) {
            $copyright_service = $store['copyright_service'];
        }
        if ($store['copyright_tutorial']) {
            $tutorial = $this->getTutorial((string) $store['copyright_tutorial']);
        }
        $teamInfo   = [
            'about_name' => $copyright_name,
            'ecology' => $tutorial,
            'service_wx' => $copyright_service,
        ];
        $teamFields = [
            'about_name' => '版权信息',
            'ecology' => '系统教程',
            'service_wx' => '专属客服',
        ];
        $team       = [];
        foreach ($teamFields as $field => $label) {
            $team[] = [
                'title' => $label,
                'values' => $teamInfo[$field],
            ];
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
            'team' => $team,
            'product' => $product,
            'platformApp' => $platformApp,
            'platform_echarts' => $platform_echarts
        ];
        return $this->successRes($data);
    }

    /**
     * 获取系统教程
     * @param mixed $tutorial
     * @return string
     * @author John
     */
    private function getTutorial(string $tutorialConfig): string
    {
        $tutorialArray = explode("\n", $tutorialConfig);
        $tutorial      = '';
        if ($tutorialConfig) {
            $tutorialArr = [];
            foreach ($tutorialArray as $value) {
                list($linkLabel, $linkValue) = explode('|', $value);
                if (!$linkLabel) {
                    continue;
                }
                if (!$linkValue) {
                    continue;
                }
                $tutorialArr[] = "<a href='{$linkValue}' target='_blank'>{$linkLabel}</a>";
            }
            $tutorial = implode('，', $tutorialArr);
        }
        return $tutorial;
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