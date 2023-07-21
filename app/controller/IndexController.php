<?php

namespace app\controller;

use app\BaseController;
use app\common\manager\SystemZipCmdMgr;
use app\common\manager\ZipMgr;
use support\Request;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
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
}
