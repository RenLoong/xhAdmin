<?php

namespace app\controller;

use app\common\BaseController;
use app\common\manager\StoreAppMgr;
use Exception;
use support\Request;
use think\facade\View;

class ArticlesController extends BaseController
{
    /**
     * 分类下的文章列表
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function index(Request $request)
    {
    }

    /**
     * 文章详情
     * @param \support\Request $request
     * @throws \Exception
     * @return string
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function detail(Request $request)
    {
        $saas_appid = $request->get('appid', '');
        $aid       = $request->get('aid', '');
        if (empty($saas_appid) || empty($aid)) {
            throw new Exception('参数错误');
        }
        $request->appid = $saas_appid;
        $storeApp = StoreAppMgr::detail(['id' => $saas_appid]);
        if (empty($storeApp)) {
            throw new Exception('应用不存在');
        }
        $class = "plugin\\{$storeApp['name']}\\app\\model\\PluginArticles";
        $model = new $class;
        $where = [
            'id' => $aid,
        ];
        $model = $model->where($where)->find();
        if (empty($model)) {
            throw new Exception('单页内容不存在');
        }
        if ($model->status !== '20') {
            throw new Exception('单页内容已禁用');
        }
        # 浏览数+1
        $model->setInc('view');
        # 渲染视图
        return View::fetch('articles/index', [
            'detail'    => $model,
            'storeApp'  => $storeApp,
        ]);
    }
}
