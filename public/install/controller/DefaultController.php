<?php
class DefaultController
{
    /**
     * 构造函数
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-19
     */
    public function __construct()
    {
        // 检测是否安装
        if (file_exists(ROOT_PATH . '/.env')) {
            exit(Json::successRes([
                'install' => 'ok',
                'desc' => '恭喜您，安装成功'
            ]));
        }
    }

    /**
     * 默认欢迎页
     *
     * @return void
     */
    public function index()
    {
        return Json::success('欢迎使用安装程序');
    }

    /**
     * 安装协议
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-20
     */
    public function agreement()
    {
        $content = file_get_contents(KF_INSTALL_PATH . '/data/agreement.txt');
        $data    = [
            'text' => $content
        ];
        return Json::successRes($data);
    }


    /**
     * 环境检测
     * @return bool|string
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    public function environment()
    {
        $data = [
            'fun' => $this->getVerifyFun(),
            'extra' => $this->getVerifyExtra(),
            'dirData' => $this->getDirData()
        ];
        return Json::successRes($data);
    }

    /**
     * 程序未安装
     *
     * @return void
     */
    public function complete()
    {
        return Json::failFul('程序未安装', 301);
    }

    /**
     * 验证目录权限
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    private function getDirData()
    {
        $dirPath = KF_INSTALL_PATH . '/config/dirList.php';
        if (!file_exists($dirPath)) {
            throw new Exception('目录权限检测配置文件错误');
        }
        $data = require_once $dirPath;
        foreach ($data as $key => $value) {
            if (!is_writable($value['dir'])) {
                $data[$key]['status'] = false;
                $data[$key]['value']  = '无写入权限';
                continue;
            }
            if (!is_readable($value['dir'])) {
                $data[$key]['status'] = false;
                $data[$key]['value']  = '无可读权限';
                continue;
            }
            $data[$key]['status'] = true;
            $data[$key]['value']  = 'Ok';
        }
        return $data;
    }

    /**
     * 获取需要验证的开启函数
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    private function getVerifyFun()
    {
        $funPath = KF_INSTALL_PATH . '/config/func.php';
        if (!file_exists($funPath)) {
            throw new Exception('禁用函数配置文件错误');
        }
        $data = require_once $funPath;
        foreach ($data as $key => $value) {
            $data[$key]['status'] = function_exists($value['name']) ? true : false;
            $data[$key]['value']  = function_exists($value['name']) ? 'OK' : 'Fail';
        }
        return $data;
    }


    /**
     * 验证需要开启的扩展
     * @return array
     * @copyright 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-05-17
     */
    private function getVerifyExtra()
    {
        $funPath = KF_INSTALL_PATH . '/config/extra.php';
        if (!file_exists($funPath)) {
            throw new Exception('扩展配置文件错误');
        }
        $data = require_once $funPath;
        foreach ($data as $key => $value) {
            switch ($value['type']) {
                case 'extra':
                    $data[$key]['status'] = extension_loaded($value['name']) ? true : false;
                    $data[$key]['value'] = extension_loaded($value['name']) ? 'OK' : 'Fail';
                    break;
                case 'class':
                    $data[$key]['status'] = class_exists($value['name']) ? true : false;
                    $data[$key]['value'] = class_exists($value['name']) ? 'OK' : 'Fail';
                    break;
                case 'function':
                    $data[$key]['status'] = function_exists($value['name']) ? true : false;
                    $data[$key]['value'] = function_exists($value['name']) ? 'OK' : 'Fail';
                    break;
                case 'version':
                    if ($value['name'] === 'php') {
                        $max                  = (bool) version_compare(PHP_VERSION, $value['min'], '>=');
                        $min                  = (bool) version_compare(PHP_VERSION, $value['max'], '<');
                        $data[$key]['status'] = $max && $min;
                        $data[$key]['value']  = $value['status'] ? 'OK' : "{$value['name']}必须是 >= {$value['min']} < {$value['max']}";
                    }
                    break;
            }
        }
        return $data;
    }
}