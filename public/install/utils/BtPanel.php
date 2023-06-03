<?php

/**
 * @title 宝塔服务面板
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class BtPanel
{
    # 接口密钥
    private $BT_KEY = "";
    # 面板地址
    private $BT_PANEL = "";

    /**
     * 构造函数
     *
     * @param string $BT_PANEL
     * @param string $BT_KEY
     */
    public function __construct(string $BT_PANEL, string $BT_KEY)
    {
        $this->BT_PANEL = $BT_PANEL;
        $this->BT_KEY = $BT_KEY;
    }

    /**
     * 获取软件列表
     *
     * @param array $data
     * @return array
     */
    public function getSoftList(array $data = []): array
    {
        $data = array_merge([
            'p' => 1,
            'type' => -1,
            'tojs' => 'soft.get_list',
            'force' => 0,
            'query' => ''
        ], $data);
        return $this->send('/plugin?action=get_soft_list', $data);
    }

    /**
     * 获取站点配置
     *
     * @param  array $data
     * @return array
     */
    public function getFileBody(array $data = []): array
    {
        $data = array_merge([
            'path' => '',
        ], $data);
        return $this->send('/files?action=GetFileBody', $data);
    }

    /**
     * 保存站点配置
     *
     * @param  array $data
     * @return array
     */
    public function SaveFileBody(array $data = []): array
    {
        $data = array_merge([
            'path' => '',
        ], $data);
        return $this->send('/files?action=SaveFileBody', $data);
    }

    /**
     * 保存守护进程
     *
     * @param  array $data
     * @return void
     */
    public function saveSupervisor(array $data = [])
    {
        $data = array_merge([
            'pjname' => '',
            'user' => 'root',
            'path' => '',
            'command' => '',
            'numprocs' => 1,
        ], $data);
        return $this->send('/plugin?action=a&name=supervisor&s=AddProcess', $data);
    }

    /**
     * 发送接口请求
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    private function send(string $url, array $data = []): array
    {
        # 拼接URL地址
        $url = $this->BT_PANEL . $url;

        # 取签名
        $p_data = $this->GetKeyData();
        $p_data = array_merge($p_data, $data);

        # 请求面板接口
        $result = $this->HttpPostCookie($url, $p_data);

        # 解析JSON数据
        $data = json_decode($result, true);
        return $data;
    }

    /**
     * 构造带有签名的关联数组
     *
     * @return array
     */
    private function GetKeyData(): array
    {
        $now_time = time();
        $p_data = array(
            'request_token'     => md5($now_time . '' . md5($this->BT_KEY)),
            'request_time'      => $now_time
        );
        return $p_data;
    }

    /**
     * 发起POST请求
     * @param String $url 目标网填，带http://
     * @param Array|String $data 欲提交的数据
     * @return string
     */
    private function HttpPostCookie($url, $data, $timeout = 60)
    {
        //定义cookie保存位置
        $cookieFile = ROOT_PATH . '/runtime/bt/' . md5($this->BT_PANEL) . '.cookie';
        $cookieDir = dirname($cookieFile);
        if (!is_dir($cookieDir)) {
            mkdir($cookieDir);
        }
        if (!file_exists($cookieFile)) {
            $fp = fopen($cookieFile, 'w+');
            fclose($fp);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
