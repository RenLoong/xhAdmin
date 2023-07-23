<?php

namespace app\store\controller;

use app\BaseController;
use app\common\enum\PlatformTypes;
use app\common\model\Store;
use app\common\model\StoreApp;
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
    public function countData(Request $request)
    {
        $admin_id = hp_admin_id('hp_store');
        if (!$admin_id) {
            return $this->failFul('登录超时，请重新登录',12000);
        }
        $where = [
            'id' => $admin_id
        ];
        $storeModel = Store::where($where)->find();
        if (!$storeModel) {
            return $this->failFul('该用户不存在',12000);
        }
        $platformApp = PlatformTypes::toArray();
        $platformList = [];
        foreach ($platformApp as $value) {
            $where          = [
                'platform'  => $value['value'],
                'store_id'  => $storeModel['id']
            ];
            $created        = StoreApp::where($where)->count();
            $num            = $storeModel[$value['value']] ?? 0;
            $item           = [
                'label'     => $value['text'],
                'key'       => $value['value'],
                'logo'      => $value['icon'],
                'created'   => $created,
                'num'       => $num,
            ];
            $platformList[] = $item;
        }
        $data = [
            'platformApp'   => $platformList
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