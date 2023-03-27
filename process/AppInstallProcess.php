<?php

namespace process;

use app\admin\service\kfcloud\CloudService;
use app\admin\service\kfcloud\HttpService;
use app\utils\Zip;
use Webman\Http\Response;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\ServerSentEvents;

/**
 * 应用安装处理进程
 *
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-24
 */
class AppInstallProcess
{

    /**
     * 消息处理
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-24
     * @param  TcpConnection $connection
     * @param  Request       $request
     * @return void
     */
    public function onMessage(TcpConnection $connection, Request $request)
    {
        if ($request->header('accept') === 'text/event-stream') {
            $headers = [
                'Access-Control-Allow-Origin'       => '*',
                'Content-Type'                      => 'text/event-stream',
            ];
            $initify = [
                'event'         => 'pageinfo',
                'data'          => [
                    'step'      => 'file',
                    'text'      => '下载代码...',
                    'next'      => 'filed',
                ],
            ];
            $connection->send(new Response(200, $headers));
            $this->sendRes($connection, $initify, 'pageinfo');
            // 获取插件
            $query = $request->get();
            if (!isset($query['id'])) {
                $this->sendRes($connection, [
                    'code'      => 404,
                    'msg'       => '插件ID错误',
                ], 'error');
                return;
            }
            // 获取插件信息
            $plugin = CloudService::detail($query['id'])->array();
            if ($plugin['code'] !== 200) {
                $this->sendRes($connection, [
                    'code'      => $plugin['code'],
                    'msg'       => $plugin['msg'],
                ], 'error');
                return;
            }
            switch ($query['step']) {
                    // 执行文件下载
                case 'file':
                    $step = [
                        'step'          => 'file',
                        'plugin_id'     => $query['id'],
                        'text'          => '下载代码...',
                        'status'        => 'success',
                        'next'          => 'filed',
                    ];
                    $this->sendRes($connection, $step, 'pageinfo');
                    // 获取下载KEY
                    $response = $this->getPluginKey($query['id'], $query['step']);
                    if ($response['code'] !== 200) {
                        $this->sendRes($connection, [
                            'code'      => $response['code'],
                            'msg'       => $response['msg'],
                        ], 'error');
                        return;
                    }
                    // 下载文件
                    $this->downloadFile($connection, $response['data'], $step);
                    break;
                    // 执行文件安装
                case 'filed':
                    $step = [
                        'step'          => 'filed',
                        'plugin_id'     => $query['id'],
                        'text'          => '安装代码...',
                        'status'        => 'warning',
                        'next'          => 'database',
                    ];
                    $this->sendRes($connection, $step, 'pageinfo');
                    $this->installPack($connection, $step);
                    break;
                    // 执行数据下载
                case 'database':
                    $step = [
                        'step'          => 'database',
                        'plugin_id'     => $query['id'],
                        'text'          => '下载SQL...',
                        'status'        => 'success',
                        'next'          => 'databased',
                    ];
                    $this->sendRes($connection, $step, 'pageinfo');
                    // 下一步
                    sleep(1);
                    $this->mockInstall($connection, $request, $step);
                    break;
                    // 执行数据安装
                case 'databased':
                    $step = [
                        'step'          => 'database',
                        'plugin_id'     => $query['id'],
                        'text'          => '安装SQL...',
                        'status'        => 'warning',
                        'next'          => '',
                    ];
                    $this->sendRes($connection, $step, 'pageinfo');
                    // 下一步
                    sleep(1);
                    $this->mockInstall($connection, $request, $step);
                    break;
            }
        }
    }

    /**
     * 模拟安装
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-24
     * @param  TcpConnection $connection
     * @param  Request       $request
     * @param  array         $step
     * @return void
     */
    private function mockInstall(TcpConnection $connection, Request $request, array $step)
    {
        for ($i = 0; $i < 100; $i++) {
            usleep(50000);
            $i += rand(0.1, 1);
            $this->sendRes($connection, ['progress' => round($i, 2)]);
        }
        // 返回结尾
        $this->sendRes($connection, ['progress' => 100, 'next' => $step['next']]);
        usleep(500000);
    }

    /**
     * 安装应用
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-25
     * @param  TcpConnection $connection
     * @param  array         $data
     * @param  array         $step
     * @return void
     */
    private function installPack(TcpConnection $connection, array $step)
    {
        try {
            $packPath = runtime_path('/temp.zip');
            $pluginPath = base_path('/plugin/a1/');
            // 开始解压
            Zip::zipExport($packPath, $pluginPath, true);
            // 返回结尾
            $this->sendRes($connection, ['progress' => 100, 'next' => $step['next']]);
            usleep(500000);
        } catch (\Throwable $e) {
            $this->sendRes($connection, [
                'msg'       => $e->getMessage(),
                'code'      => $e->getCode()
            ], 'error');
        }
    }

    /**
     * 安装SQL
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-25
     * @param  TcpConnection $connection
     * @param  array         $data
     * @return void
     */
    private function installDatabase(TcpConnection $connection, array $data)
    {
        $packPath = runtime_path('/temp.sql');
    }

    /**
     * 下载文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-25
     * @param  TcpConnection $connection
     * @param  array         $data
     * @param  array         $step
     * @return void
     */
    private function downloadFile(TcpConnection $connection, array $data, array $step)
    {
        $key = $data['key'];
        $format = $data['format'];
        // 获取安装文件
        $host = HttpService::$host;
        $url = "{$host}Plugin/install?key={$key}";
        // header
        $header = get_headers($url, true);
        // 检测文件大小
        if (!isset($header['Content-Length'])) {
            $this->sendRes($connection, [
                'code'      => 404,
                'msg'       => '插件ID错误',
            ], 'error');
            return;
        }
        // 文件大小（字节）
        $fileSize = $header['Content-Length'];
        // 远程文件
        $remote = fopen($url, 'rb');
        if (!$remote) {
            $this->sendRes($connection, [
                'code'      => 404,
                'msg'       => '远程源文件错误',
            ], 'error');
            return;
        }
        // 缓存文件路径
        $savePath = runtime_path("/temp.{$format}");
        // 本地文件
        $local = fopen($savePath, 'wb');
        if (!$local) {
            $this->sendRes($connection, [
                'code'      => 404,
                'msg'       => '本地源文件错误',
            ], 'error');
            return;
        }
        // 每次写入字节（1024=1kb）
        $chunk = 4096;
        // 分段读取文件
        $downlen = 0;
        while (!feof($remote)) {
            // 读取流
            $stream = fread($remote, $chunk);
            // 写入文件
            fwrite($local, $stream, $chunk);
            // 获得块大小
            $downlen += strlen($stream);
            // 计算百分比
            $percent = round($downlen / $fileSize * 100, 2);

            if ($percent < 100) {
                $this->sendRes($connection, ['progress' => $percent]);
            }
        }
        fclose($local);
        fclose($remote);
    }

    /**
     * 下载文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-25
     * @param  TcpConnection $connection
     * @param  array         $data
     * @return void
     */
    private function downloadFile1(TcpConnection $connection, array $data, array $step)
    {
        $key = $data['key'];
        $format = $data['format'];
        // 获取安装文件
        $file = CloudService::install($key);
        // 远程文件路径
        // $url = "{$host}Plugin/install?key={$key}";
        // 缓存文件路径
        $savePath = runtime_path("/temp.{$format}");
        // 文件大小（BT）
        $fileSize = $file->header('Content-Length');
        // 远程文件
        $fp = $file->body();
        // $fp = fopen($url, 'rb');
        // 本地文件
        $localFile = fopen($savePath, 'w');
        // 每次分片大小
        $chunk_size = 1024 * 1024 * 1; // 1MB
        // 累计下载大小
        $downlen = 0;
        // 下载总大小
        $last = 0;
        $diff = 0;
        // 分段读取文件
        while (!feof($fp)) {
            // 读取流
            $stream_size = fread($fp, $chunk_size);
            // 写入文件
            fwrite($localFile, $stream_size);
            // 获得块大小
            $downlen += strlen($stream_size);
            // 计算百分比
            $percent = round($downlen / $fileSize * 100, 2);
            $diff += $percent - $last;
            if ($diff > 1) {
                $diff = 0;
            }
            $last = $percent;
            if ($last < 100) {
                $this->sendRes($connection, ['progress' => $last]);
            }
        }
        fclose($fp);
        // 返回结尾
        $this->sendRes($connection, ['progress' => 100, 'next' => $step['next']]);
    }

    /**
     * 获取插件数据
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-25
     * @param  integer $plugin_id
     * @param  string  $step
     * @return array
     */
    private function getPluginKey(int $plugin_id, string $step): array
    {
        // 获取插件
        $response = CloudService::getDownKey(
            (int) $plugin_id,
            (string) $step
        )->array();
        return $response;
    }

    /**
     * 返回消息
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-24
     * @param  TcpConnection $connection
     * @param  array         $data
     * @param  string        $eventName
     * @return void
     */
    private function sendRes(TcpConnection $connection, array $data, string $eventName = 'progress')
    {
        $serverMsg['event'] = $eventName;
        $serverMsg['data'] = json_encode($data, 256);
        $connection->send(new ServerSentEvents($serverMsg));
    }
}
