<?php

// +----------------------------------------------------------------------
// | WeChatDeveloper
// +----------------------------------------------------------------------
// | 版权所有 2014~2023 ThinkAdmin [ thinkadmin.top ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// | 免责声明 ( https://thinkadmin.top/disclaimer )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/WeChatDeveloper
// | github 代码仓库：https://github.com/zoujingli/WeChatDeveloper
// +----------------------------------------------------------------------

try {

    // 1. 手动加载入口文件
    include "../include.php";

    // 2. 准备公众号配置参数
    $config = include "./config.php";

    // 3. 创建接口实例
    // $wechat = new \WeChat\Pay($config);
    // $wechat = \We::WeChatPay($config);
    $wechat = \WeChat\Pay::instance($config);

    // 4. 组装参数，可以参考官方商户文档
    $options = [
        'transaction_id' => '1008450740201411110005820873',
        // 'out_trade_no'   => '商户订单号',
        // 'out_refund_no' => '商户退款单号'
        // 'refund_id' => '微信退款单号',
    ];
    $result = $wechat->queryRefund($options);

    var_export($result);

} catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;

}