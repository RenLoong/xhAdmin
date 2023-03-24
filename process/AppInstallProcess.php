<?php

namespace process;

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
            $query = $request->get();
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
            switch ($query['step']) {
                    // 执行文件下载
                case 'file':
                    $step = [
                        'step'      => 'file',
                        'text'      => '下载代码...',
                        'status'    => 'success',
                        'next'      => 'filed',
                    ];
                    $this->sendRes($connection, $step, 'pageinfo');
                    // 下一步
                    $this->mockInstall($connection, $request, $step);
                    // $this->downloadFile($connection, $request, $step);
                    break;
                    // 执行文件安装
                case 'filed':
                    $step = [
                        'step'      => 'filed',
                        'text'      => '安装代码...',
                        'status'    => 'warning',
                        'next'      => 'database',
                    ];
                    $this->sendRes($connection, $step, 'pageinfo');
                    // 下一步
                    sleep(1);
                    $this->mockInstall($connection, $request, $step);
                    break;
                    // 执行数据下载
                case 'database':
                    $step = [
                        'step'      => 'database',
                        'text'      => '下载SQL...',
                        'status'    => 'success',
                        'next'      => 'databased',
                    ];
                    $this->sendRes($connection, $step, 'pageinfo');
                    // 下一步
                    sleep(1);
                    $this->mockInstall($connection, $request, $step);
                    break;
                    // 执行数据安装
                case 'databased':
                    $step = [
                        'step'      => 'database',
                        'text'      => '安装SQL...',
                        'status'    => 'warning',
                        'next'      => '',
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
     * 下载文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-24
     * @param  TcpConnection $connection
     * @param  Request       $request
     * @return void
     */
    private function downloadFile(TcpConnection $connection, Request $request, array $step)
    {
        $url = "http://server8.saisaitu.net/1.zip";
        $savePath = base_path('/plugin/') . '1.zip';
        $fileHeaders = get_headers($url, true);
        if (!isset($fileHeaders['Content-Length'])) {
            $this->sendRes($connection, ['msg' => '获取文件大小失败'], 'error');
        }
        // 文件大小（BT）
        $fileSize = $fileHeaders['Content-Length'];
        // 打开远程文件
        $fp = fopen($url, 'rb');
        // 分片大小
        $chunk_size = 1024 * 1024 * 1; // 1MB
        // 下载大小
        $downlen = 0;
        $last = 0;
        $diff = 0;
        // 分段读取文件
        while (!feof($fp)) {
            // 读取流
            $stream_size = fread($fp, $chunk_size);
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
