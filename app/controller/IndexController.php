<?php

namespace app\controller;

use app\BaseController;
use app\common\manager\PhpZipArchiveMgr;
use app\common\manager\SystemZipCmdMgr;
use app\common\manager\ZipMgr;
use app\common\service\SystemUpdateService;
use app\common\service\UpdateDataService;
use support\Request;
use think\facade\Db;

class IndexController extends BaseController
{
    /**
     * 默认访问
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function index(Request $request)
    {
        // 检测是否安装
        if (!file_exists(base_path('/.env'))) {
            return redirect('/install/');
        }
        return redirect('/store/');
    }

    /**
     * 测试专用
     * @param \support\Request $request
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function test(Request $request)
    {
        # 测试框架升级
        $updateService = new UpdateDataService($request,1000);
        $data = $updateService->beforeUpdate();
        $updateService->update($data);
        
        # 测试PHP内置解压管理器
        // (new PhpZipArchiveMgr)->unzip(base_path('test123.zip'),base_path('/test123'));
        # 测试PHP内置打包管理器
        // (new PhpZipArchiveMgr)->buildFiles(base_path('test123.zip'),public_path(), [
        //     'remote',
        //     '404.html',
        //     'index.php'
        // ]);

        # 测试打包
        // ZipMgr::build(base_path('test123.zip'), base_path('public'),[
        //     '404.html',
        //     'image/1.jpg',
        // ]);

        # 测试解压
        // ZipMgr::unzip(base_path('test123.zip'), base_path('/jieya/packages'));
        
        // SystemZipCmdMgr::zipBuildCmd(base_path('runtime/test123.zip'), base_path('public'));
        return $this->fail('测试失败');
    }

    /**
     * 更新测试
     * @param \support\Request $request
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function checkUpdate(Request $request)
    {
        // $funcName = $request->get('step', '');
        // $version  = (int) $request->get('version', 0);
        // if (empty($version)) {
        //     return $this->fail('更新目标版本参数错误');
        // }
        // if (empty($funcName)) {
        //     return $this->fail('操作方法出错');
        // }
        // $class = new SystemUpdateService($request);
        // return call_user_func([$class, $funcName]);
    }
}
