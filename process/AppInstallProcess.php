<?php

namespace process;

use app\admin\service\kfcloud\CloudService;
use app\admin\service\kfcloud\HttpService;
use Exception;
use FFI;
use think\facade\Db;
use Webman\Http\Response;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\ServerSentEvents;
use ZipArchive;

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
     * 当前对象
     *
     * @var TcpConnection
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     */
    private $connection;

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
        $this->connection = $connection;
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
            $this->sendRes($initify, 'pageinfo');
            // 获取插件
            $query = $request->get();
            if (!isset($query['id'])) {
                $this->sendRes([
                    'code'      => 404,
                    'msg'       => '插件ID错误',
                ], 'error');
                return;
            }
            // 获取插件信息
            $plugin = CloudService::detail($query['id'])->array();
            if ($plugin['code'] !== 200) {
                $this->sendRes([
                    'code'      => $plugin['code'],
                    'msg'       => $plugin['msg'],
                ], 'error');
                return;
            }
            // 处理安装步骤
            $this->checkStep($request, $query);
        }
    }

    /**
     * 处理安装步骤
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     * @param  Request $request
     * @param  array   $query
     * @return void
     */
    private function checkStep(Request $request, array $query)
    {
        $step = [
            'step'          => 'database',
            'plugin_id'     => $query['id'],
            'text'          => '安装SQL...',
            'status'        => 'warning',
            'next'          => 'lock',
        ];
        // 安装文件已存在（直接下一步）
        $tempFile = runtime_path('/temp.txt');
        if (!is_file($tempFile)) {
            return $this->sendRes([
                'code'      => 404,
                'msg'       => '找不到SQL更新文件',
            ], 'error');
        }
        return $this->installSQL($tempFile, $step);
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
                $this->sendRes($step, 'pageinfo');
                // 获取下载KEY
                $response = $this->getPluginKey($query['id'], $query['step']);
                if ($response['code'] !== 200) {
                    return $this->sendRes([
                        'code'      => $response['code'],
                        'msg'       => $response['msg'],
                    ], 'error');
                }
                // 安装文件已存在（直接下一步）
                $tempFile = runtime_path('/temp.zip');
                if (is_file($tempFile)) {
                    sleep(1);
                    return $this->sendRes(['progress' => 100, 'next' => $step['next']]);
                }
                // 下载文件
                return $this->downloadFile($tempFile, $response['data'], $step);
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
                $this->sendRes($step, 'pageinfo');
                $packPath = runtime_path('/temp.zip');
                return $this->installPack($packPath, $step);
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
                $this->sendRes($step, 'pageinfo');
                // 获取下载KEY
                $response = $this->getPluginKey($query['id'], $query['step']);
                if ($response['code'] !== 200) {
                    return $this->sendRes([
                        'code'      => $response['code'],
                        'msg'       => $response['msg'],
                    ], 'error');
                }
                // 安装文件已存在（直接下一步）
                $tempFile = runtime_path('/temp.txt');
                if (is_file($tempFile)) {
                    sleep(1);
                    return $this->sendRes(['progress' => 100, 'next' => $step['next']]);
                }
                return $this->downloadFile($tempFile, $response['data'], $step);
                break;
                // 执行数据安装
            case 'databased':
                $step = [
                    'step'          => 'database',
                    'plugin_id'     => $query['id'],
                    'text'          => '安装SQL...',
                    'status'        => 'warning',
                    'next'          => 'lock',
                ];
                $this->sendRes($step, 'pageinfo');
                // 安装文件已存在（直接下一步）
                $tempFile = runtime_path('/temp.txt');
                if (!is_file($tempFile)) {
                    return $this->sendRes([
                        'code'      => 404,
                        'msg'       => '找不到SQL更新文件',
                    ], 'error');
                }
                return $this->installSQL($tempFile, $step);
                break;
                // 应用安装完成
            case 'lock':
                $step = [
                    'step'          => 'lock',
                    'plugin_id'     => $query['id'],
                    'text'          => '安装完成',
                    'status'        => 'success',
                    'next'          => '',
                ];
                $this->sendRes($step, 'pageinfo');
                break;
        }
    }

    /**
     * 安装SQL
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-30
     * @param  string $tempFile
     * @param  array  $step
     * @return void
     */
    private function installSQL(string $tempFile, array $step)
    {
        try {
            // 替换SQL模板文件内容
            $str = file_get_contents($tempFile);
            $str = preg_replace('/--.*/i', '', $str);
            $str = preg_replace('/\/\*(.*)\*\//i', '', $str);
            //去除空格 创建数组
            $arr = explode(";", $str);
            // 去空数组
            $arr = array_filter($arr);
            file_put_contents(runtime_path('/sql.txt'), var_export($arr, true));
            // 总数
            // $count = count($arr);
            // // 执行SQL
            // foreach ($arr as $key => $sql) {
            //     $sql = trim($sql);
            //     Db::query("{$sql};");
            //     // 获得进度
            //     $progress = round(($key / $count) * 100, 2);
            //     $this->sendRes(['progress' => $progress]);
            // }
        } catch (\Throwable $e) {
            return $this->sendRes([
                'msg'       => $e->getMessage(),
                'code'      => $e->getCode()
            ], 'error');
        }
        // 返回结尾
        return $this->sendRes(['progress' => 100, 'next' => $step['next']]);
    }

    /**
     * 安装应用
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     * @param  string $packPath
     * @param  array  $step
     * @return void
     */
    private function installPack(string $packPath, array $step)
    {
        try {
            $pluginPath = base_path('/plugin/');
            // 开始解压（覆盖安装）
            $this->unzipFile($packPath, $pluginPath, true, $step);
            // 返回结尾
            return $this->sendRes(['progress' => 100, 'next' => $step['next']]);
        } catch (\Throwable $e) {
            $this->sendRes([
                'msg'       => $e->getMessage(),
                'code'      => $e->getCode()
            ], 'error');
        }
    }

    /**
     * 解压安装文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     * @param  string      $source_path
     * @param  string|null $target_path
     * @param  boolean     $force_cover
     * @return void
     */
    private function unzipFile(string $source_path, string $target_path = null, bool $force_cover = false)
    {
        if ($target_path === null) {
            $target_path = dirname($source_path);
        }
        if (!file_exists($target_path)) {
            throw new Exception("目标路径 {$target_path} 不存在");
        } else if (!is_dir($target_path)) {
            throw new Exception("目标路径 {$target_path} 必须是目录");
        }
        $source_path = realpath($source_path);
        $target_path = realpath($target_path);

        $zip_resource = new ZipArchive;
        if ($zip_resource->open($source_path) === true) {
            $numFiles = $zip_resource->numFiles;
            for ($i = 0; $i < $numFiles; $i++) {
                $index_stat = $zip_resource->statIndex($i);
                $index_file_name = $index_stat['name'];
                $out_path = "$target_path/$index_file_name";
                if (file_exists($out_path) && !$force_cover) {
                    throw new Exception("不允许覆盖，如需强制覆盖请将第3个参数设为false");
                }
                if ($index_stat['crc'] != 0) {
                    $zip_resource->extractTo($target_path, $index_file_name);
                    // 获得进度
                    $progress = round(($i / $numFiles) * 100, 2);
                    $this->sendRes(['progress' => $progress]);
                } else {
                    @mkdir($out_path, 0777);
                }
            }
        } else {
            throw new Exception("zip文件打开失败");
        }
        $zip_resource->close();
        // 删除临时文件
        unlink($source_path);
    }

    /**
     * 下载文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     * @param  string $path
     * @param  array  $data
     * @param  array  $step
     * @return void
     */
    private function downloadFile(string $savePath, array $data, array $step)
    {
        $key = $data['key'];
        // 获取安装文件
        $host = HttpService::$host;
        $url = "{$host}Plugin/install?key={$key}";
        $this->curlDownload($url, $savePath, $step);
    }

    /**
     * 使用CURL方式下载保存文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     * @param  string         $url
     * @param  string         $savePath
     * @param  array          $step
     * @return string|boolean
     */
    private function curlDownload(string $url, string $savePath, array $step): string|bool
    {
        // 本地文件
        $local = fopen($savePath, 'wb');
        if (!$local) {
            return $this->sendRes([
                'code'      => 404,
                'msg'       => '本地源文件错误',
            ], 'error');
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
        curl_setopt($ch, CURLOPT_FILE, $local);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, [$this, 'progress']);
        $response = curl_exec($ch);
        curl_close($ch);
        fclose($local);
        // 输出最后结尾
        $this->sendRes(['progress' => 100, 'next' => $step['next']]);
        return $response;
    }

    /**
     * 下载进度显示
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     * @param  type $ch CURL资源句柄
     * @param  type $total 文件总大小
     * @param  type $current 当前已下载
     * @param  type $uploadSize
     * @param  type $uploaded
     * @return void
     */
    private function progress($ch, $total, $current, $uploadSize, $uploaded)
    {
        if ($total == 0) {
            return;
        }
        if ($current > $total) {
            $current = $total;
        }
        $total = (float) $total;
        $current = (float) $current;
        // 当前进度
        $progress = round(((float)$current / (float)$total) * 100, 2);
        $this->sendRes(['progress' => $progress]);
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
     * 输出消息
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-28
     * @param  array  $data
     * @param  string $eventName
     * @return string
     */
    private function sendRes(array $data, string $eventName = 'progress'): string
    {
        $serverMsg['event'] = $eventName;
        $serverMsg['data'] = json_encode($data, 256);
        $this->connection->send(new ServerSentEvents($serverMsg));
        return '';
    }
}
