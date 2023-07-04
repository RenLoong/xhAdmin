<?php
/**
 * 宝塔逻辑类
 */
class BtPanelLogic
{
    /**
     * 宝塔接口类
     * @var BtPanel
     */
    private $btPanel = null;

    /**
     * 构造函数
     * @param mixed $BT_PANEL
     * @param mixed $BT_KEY
     * @param mixed $BT_SSL
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct(string $BT_PANEL, string $BT_KEY,bool $BT_SSL)
    {
        $this->btPanel = new BtPanel($BT_PANEL,$BT_KEY,$BT_SSL);
    }

    /**
     * 获取网络状态
     * @throws \Exception
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function validateBtConnect()
    {
        $data = $this->btPanel->GetNetWork();
        if (empty($data)) {
            throw new Exception('请检查宝塔端口或密钥是否正确');
        }
    }

    /**
     * 获取nginx配置
     *
     * @return array
     */
    public function getNginxConfig():array
    {
        $data=$this->btPanel->getSiteDomains();
        if(empty($data['data'])){
            throw new Exception('未获取到当前域名站点信息');
        }
        // $siteDirName = basename(ROOT_PATH);
        $configPath = "/www/server/panel/vhost/nginx/{$data['data'][0]['name']}.conf";
        $response = $this->btPanel->getFileBody(['path' => $configPath]);
        if (!isset($response['data'])) {
            throw new Exception('无法获取到Nginx配置文件');
        }
        return [
            'config_path'       => $configPath,
            'data'              => $response['data']
        ];
    }

    /**
     * 安装nginx配置
     *
     * @param  string $server_name
     * @param  array  $data
     * @return void
     */
    public function addNginx(string $server_name, array $data)
    {
        $response = $this->getNginxConfig();
        # nginx配置文件路径
        $configPath = $response['config_path'];
        # 宝塔Nginx配置内容
        $nginxConfig = $response['data'];
        # 设置nginx---upstream
        $nginxUpstream = file_get_contents(KF_INSTALL_PATH . '/data/nginx/nginx_upstream.tpl');
        # 检测已安装nginx
        if (strpos($nginxConfig, $server_name) !== false) {
            # 设置nginx---upstream
            $newNginxConfig = preg_replace('/server 127.0.0.1:(.*);/', "server 127.0.0.1:{$data['serverData']['server_port']};", $nginxConfig);
        } else {
            # 设置nginx---upstream
            $str1 = ['{SERVER_NAME}', '{SERVER_PORT}'];
            $str2 = [$server_name, $data['serverData']['server_port']];
            $nginxUpstream = str_replace($str1, $str2, $nginxUpstream);
            $newNginxConfig = "{$nginxUpstream}\n{$nginxConfig}";
            # 设置nginx---server
            $nginxUpstream = file_get_contents(KF_INSTALL_PATH . '/data/nginx/nginx_server.tpl');
            $str1 = ['{SERVER_NAME}'];
            $str2 = [$server_name];
            $nginxUpstream = str_replace($str1, $str2, $nginxUpstream);
            $sslEnd = "#SSL-END\n\n\n{$nginxUpstream}\n\n\n";
            $newNginxConfig = str_replace("#SSL-END", $sslEnd, $newNginxConfig);
        }
        // 保存nginx配置
        $data = [
            'path'      => $configPath,
            'encoding'  => 'utf-8',
            'data'      => $newNginxConfig
        ];
        $response = $this->btPanel->SaveFileBody($data);
        if (!isset($response['status']) || !$response['status']) {
            throw new Exception('保存Nginx站点配置失败');
        }
    }

    /**
     * 获取软件列表
     *
     * @return array
     */
    public function getSoftList():array
    {
        $list = [];
        for ($i=1; $i < 10; $i++) {
            $response = $this->btPanel->getSoftList(['p' => $i]);
            if (!isset($response['list']['data'])) {
                throw new Exception('获取软件列表失败');
            }
            if (empty($response['list']['data'])) {
                continue;
            }
            $list = array_merge($list, $response['list']['data']);
        }
        return $list;
    }

    /**
     * 获取已安装软件列表
     *
     * @return array
     */
    public function getSoftNames():array
    {
        $list = $this->getSoftList();
        $names = [];
        foreach ($list as $value) {
            $names[] = $value['name'];
        }
        return $names;
    }

    /**
     * 验证是否安装某个软件
     *
     * @param string $name
     * @return void
     */
    public function validateSoft(string $name)
    {
        $names = $this->getSoftNames();
        if (!in_array($name, $names)) {
            throw new Exception("宝塔 {$name} 未安装");
        }
    }

    /**
     * 获取守护进程列表
     *
     * @return array
     */
    public function getSupervisorList():array
    {
        return $this->btPanel->getSupervisorList();
    }

    /**
     * 获取守护进程名称列表
     *
     * @return array
     */
    public function supervisorNames():array
    {
        $supervisorList = $this->getSupervisorList();
        $supervisorName = [];
        foreach ($supervisorList as $value) {
            $supervisorName[] = isset($value['program']) ? $value['program'] : '';
        }
        return $supervisorName;
    }

    /**
     * 验证守护进程服务是否已安装
     *
     * @param string $server_name
     * @return void
     */
    public function valiSupervisorNames(string $server_name)
    {
        $supervisorNames = $this->supervisorNames();
        if (in_array($server_name, $supervisorNames)) {
            throw new Exception("守护进程 {$server_name} 已安装");
        }
    }

    /**
     * 安装守护进程
     *
     * @param  string $server_name
     * @param  array  $data
     * @return void
     */
    public function addSupervisor(string $server_name)
    {
        $supervisorName = $this->supervisorNames($server_name);
        // 已安装守护进程，不再安装
        if (in_array($server_name, $supervisorName)) {
            return;
        }
        // 保存守护进程数据
        $queryData = [
            'pjname'    => $server_name,
            'user'      => 'root',
            'path'      => ROOT_PATH . '/',
            'command'   => 'php webman start',
            'numprocs'  => 1,
            'ps'        => $server_name
        ];
        $response = $this->btPanel->saveSupervisor($queryData);
        if (isset($response['status']) && !$response['status']) {
            throw new Exception("{$response['msg']}，安装守护进程失败");
        }
    }

    /**
     * 重启守护进程
     *
     * @param string $server_name
     * @return void
     */
    public function reloadSupervisor(string $server_name)
    {
        # 停止进程
        $response = $this->stopSupervisor($server_name);
        # 停止成功
        if ($response['status']) {
            # 启动进程
            $this->startSupervisor($server_name);
        }
    }

    /**
     * 停止守护进程
     *
     * @param string $server_name
     * @return array
     */
    public function stopSupervisor(string $server_name):array
    {
        $params = [
            'program'       => $server_name,
            'numprocs'      => 1
        ];
        return $this->btPanel->stopSupervisor($params);
    }

    /**
     * 启动守护进程
     *
     * @param string $server_name
     * @return array
     */
    public function startSupervisor(string $server_name):array
    {
        $params = [
            'program'       => $server_name,
            'numprocs'      => 1
        ];
        return $this->btPanel->startSupervisor($params);
    }
    public function __call($name, $arguments)
    {
        if (method_exists($this->btPanel, $name)) {
            return $this->btPanel->$name(...$arguments);
        }
    }
}