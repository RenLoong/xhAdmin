<?php

namespace app\admin\controller;

use app\common\manager\SystemConfigMgr;
use app\BaseController;
use Exception;
use support\Request;

/**
 * 配置项
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-07
 */
class SystemConfigController extends BaseController
{
    /**
     * 系统配置
     * @param Request $request
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
     */
    public function form(Request $request)
    {
        $configMgr    = new SystemConfigMgr($request);
        if ($request->method() == 'PUT') {
            # 提交配置项数据
            $post     = request()->post();
            # 配置表单规则
            $settings = config('settings');
            # 保存数据
            $configMgr->saveData($settings, $post);
            # 返回数据
            return $this->success('保存成功');
        }
        $settingsPath = base_path('/config/settings.php');
        if (!file_exists($settingsPath)) {
            throw new Exception('配置文件不存在');
        }
        $settings = config('settings');
        if (empty($settings)) {
            return $this->fail('配置文件数据不存在');
        }
        $data = $configMgr->getConfigView($settings);
        return parent::successRes($data);
    }
}