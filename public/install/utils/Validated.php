<?php
use app\admin\service\kfcloud\CloudService;
use YcOpen\CloudService\Cloud;
use YcOpen\CloudService\Request\LoginRequest;

class Validated
{
    # 检测默认端口是否被占用
    public static function validatePort(int $port)
    {
        $host = 'localhost';
        $fp = @fsockopen($host, $port, $errno, $errstr, 3);
        if (!$fp) {
            return false;
        }
        fclose($fp);
        return true;
    }

    /**
     * 验证宝塔验证
     * @param mixed $data
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function validateBt(array $data)
    {
        if (!isset($data['panel_port']) || empty($data['panel_port'])) {
            throw new Exception('请输入宝塔面板端口号');
        }
        if (!is_numeric($data['panel_port'])) {
            throw new Exception('请输入正确的面板端口号');
        }
        if (!isset($data['panel_key']) || empty($data['panel_key'])) {
            throw new Exception('请输入宝塔面板密钥');
        }
        $panel_ssl = isset($data['panel_ssl']) ? (bool) $data['panel_ssl'] : false;
        $bt = new BtPanelLogic($data['panel_port'], $data['panel_key'], $panel_ssl);
        # 验证宝塔是否通信成功
        $bt->validateBtConnect();
        # 验证nginx是否安装
        $bt->validateSoft('nginx');
        # 验证Mysql是否安装
        $bt->validateSoft('mysql');
        # 验证守护进程软件是否安装
        $bt->validateSoft('supervisor');
        # 升级composer
        $bt->updateComposer();

        # 守护进程服务是否已安装
        $server_name = str_replace('.', '_', basename(ROOT_PATH));
        $bt->valiSupervisorNames($server_name);
    }

    /**
     * 验证云服务
     * @param mixed $data
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function validateCloud(array $data)
    {
        $req = new LoginRequest;
        $req->login();
        $req->username = $data['username'];
        $req->password = $data['password'];
        $req->scode = 'no';
        $cloud = new Cloud($req);
        $cloud->send();
        return $data;
    }

    /**
     * 验证站点
     * @param mixed $data
     * @throws \Exception
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public static function validateSite(array $data)
    {
        if (!isset($data['web_name']) || empty($data['web_name'])) {
            throw new Exception('请输入站点名称');
        }
        if (!isset($data['web_url']) || empty($data['web_url'])) {
            throw new Exception('请输入站点域名');
        }
        if (!filter_var($data['web_url'], FILTER_VALIDATE_URL)) {
            throw new Exception('请输入正确的域名地址');
        }
        if (!isset($data['username']) || empty($data['username'])) {
            throw new Exception('请输入站点管理员账号');
        }
        if (!isset($data['password']) || empty($data['password'])) {
            throw new Exception('请输入站点管理员密码');
        }
        return $data;
    }
}