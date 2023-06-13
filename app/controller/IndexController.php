<?php

namespace app\controller;

use app\BaseController;
use app\Install;
use app\service\Upload;
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
     * 首页视图
     * @return \support\Response
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-04-29
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
     * @return \support\Response
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function test()
    {
        // $url = 'http://mj.xg.kfadmin.net/storage/image/2023-06-13/709a00b11d7b8a13d5c6e49d001e2a9d.png';
        // $data = Upload::remoteFile($url);
        // return $this->successRes($data);
    }
}
