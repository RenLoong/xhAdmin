<?php

namespace app\store\controller;

use app\BaseController;
use app\common\enum\PlatformTypes;
use app\common\manager\StorePlatforms;
use app\common\model\Store;
use app\model\Users;
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
        $store    = Store::where(['id' => $store_id])->find()->toArray();
        # 设置系统默认版权
        $copyright_name     = (string) getHpConfig('store_copyright_name');
        $copyright_service  = (string) getHpConfig('store_copyright_service');
        $copyright_tutorial = (string) getHpConfig('store_copyright_tutorial');
        $tutorial           = '';
        if ($copyright_tutorial) {
            $tutorial = $this->getTutorial((string) $copyright_tutorial);
        }

        if (!empty($store['title']) && !empty($store['copyright_service']) && !empty($store['copyright_tutorial'])) {
            $copyright_name    = $store['title'];
            $copyright_service = $store['copyright_service'];
            $tutorial          = $this->getTutorial((string) $store['copyright_tutorial']);
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
            # 查询条件
            $where = [
                ['user.store_id', '=', (int) hp_admin_id('hp_store')],
                ['platform.platform_type', '=', $value['value']],
            ];
            # 今日数据
            $today = Users::alias('user')
                ->join('store_platform platform', 'platform.id=user.platform_id')
                ->where($where)
                ->whereDay('user.create_at')
                ->count();
            # 本周数据
            $week = Users::alias('user')
                ->join('store_platform platform', 'platform.id=user.platform_id')
                ->where($where)
                ->whereWeek('user.create_at')
                ->count();
            # 本月数据
            $moon = Users::alias('user')
                ->join('store_platform platform', 'platform.id=user.platform_id')
                ->where($where)
                ->whereMonth('user.create_at')
                ->count();
            # 本季度数据
            $quarter = Users::alias('user')
                ->join('store_platform platform', 'platform.id=user.platform_id')
                ->where($where)
                ->whereTime('user.create_at', '-3 month')
                ->count();
            # 半年数据
            $half_year = Users::alias('user')
                ->join('store_platform platform', 'platform.id=user.platform_id')
                ->where($where)
                ->whereTime('user.create_at', '-6 month')
                ->count();
            # 今年数据
            $year = Users::alias('user')
                ->join('store_platform platform', 'platform.id=user.platform_id')
                ->where($where)
                ->whereYear('user.create_at')
                ->count();
            // 组装图表数据
            $platform_echarts[] = [
                'name' => $value['text'],
                'type' => 'line',
                'stack' => 'Total',
                'smooth' => true,
                'data' => [
                    #今日
                    $today,
                    // #本周
                    $week,
                    // #本月
                    $moon,
                    // #季度
                    $quarter,
                    // #半年
                    $half_year,
                    // #今年
                    $year
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
     * 获取系统教程
     * @param mixed $tutorial
     * @return string
     * @author John
     */
    private function getTutorial(string $tutorialConfig): string
    {
        $tutorialArray = explode("\n", $tutorialConfig);
        if (empty($tutorialArray)) {
            return '';
        }
        $tutorial      = '';
        $tutorialArr = [];
        foreach ($tutorialArray as $value) {
            $tutorialLink = explode('|', $value);
            if (!isset($tutorialLink[0])) {
                continue;
            }
            if (!isset($tutorialLink[1])) {
                continue;
            }
            $tutorialArr[] = "<a href='{$tutorialLink[1]}' target='_blank'>{$tutorialLink[0]}</a>";
        }
        $tutorial = implode('，', $tutorialArr);
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