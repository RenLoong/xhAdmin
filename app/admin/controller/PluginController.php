<?php

namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\admin\service\kfcloud\CloudService;
use app\BaseController;
use app\enum\PluginPlatform;
use app\enum\PluginType;
use app\exception\AppException;
use support\Request;
use Workerman\Protocols\Http\ServerSentEvents;
use yzh52521\EasyHttp\Http;
use ZipArchive;

class PluginController extends BaseController
{
    /**
     * 表格列
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-10
     * @param  Request $request
     * @return void
     */
    public function indexGetTable(Request $request)
    {
        $limit = (int)$request->get('limit', 20);
        $builder = new ListBuilder;
        $data = $builder
            ->addActionOptions('操作', [
                'width'         => 180
            ])
            ->addTopButton('cloud', '云服务', [
                'type'          => 'remote',
                'api'           => '/remote/cloud/index',
            ], [
                'title'         => '云服务中心',
                'width'         => '45%'
            ], [
                'type'          => 'success'
            ])
            ->addRightButton('doc', '文档', [
                'type'          => 'link',
                'params'        => [
                    'field'     => 'doc_url',
                ],
            ], [], [
                'type'          => 'warning'
            ])
            ->addRightButton('buy', '购买', [
                'type'          => 'remote',
                'api'           => '/remote/cloud/buy',
                'params'        => [
                    'field'     => 'plugin_status',
                    'value'     => 'buy',
                ]
            ], [
                'title'         => '购买应用',
                'width'         => '45%',
            ], [
                'type'          => 'success'
            ])
            ->addRightButton('install', '安装', [
                'type'          => 'remote',
                'api'           => '/remote/cloud/install',
                'params'        => [
                    'field'     => 'plugin_status',
                    'value'     => 'install',
                ]
            ], [
                'title'         => '应用安装',
                'width'         => '45%',
            ], [
                'type'          => 'success'
            ])
            ->addRightButton('uninstall', '卸载', [
                'type'          => 'confirm',
                'api'           => '/admin/Plugin/uninstall',
                'method'        => 'delete',
                'params'        => [
                    'field'     => 'plugin_status',
                    'value'     => 'uninstall',
                ]
            ], [
                'title'         => '温馨提示',
                'content'       => '是否确认写在该应用插件？',
            ], [
                'type'          => 'danger'
            ])
            ->tabsConfig([
                'active'        => 'install',
                'field'         => 'inst',
                'list'          => [
                    [
                        'label' => '未安装',
                        'value' => 'install',
                    ],
                    [
                        'label' => '已安装',
                        'value' => 'installed',
                    ],
                ]
            ])
            ->pageConfig([
                'pageSize'      => $limit
            ])
            ->addColumn('title', '应用名称')
            ->addColumn('version', '应用版本', [
                'width'         => 100
            ])
            ->addColumn('author.title', '开发者')
            ->addColumnEle('money', '应用价格', [
                'params'        => [
                    'type'      => 'money'
                ]
            ])
            ->addColumnEle('logo', '应用图标', [
                'width'         => 100,
                'params'        => [
                    'type'      => 'image',
                ],
            ])
            ->addColumnEle('platform', '平台类型', [
                'width'         => 120,
                'params'        => [
                    'type'      => 'tags',
                    'options'   => PluginPlatform::getDict(),
                    'props'     => [
                        'app'       => [
                            'type'  => 'success'
                        ],
                        'plugin'    => [
                            'type'  => 'danger'
                        ],
                    ],
                ],
            ])
            ->addColumnEle('plugin_type', '应用类型', [
                'width'         => 100,
                'params'        => [
                    'type'      => 'tags',
                    'options'   => PluginType::getDict(),
                    'props'     => [
                        'app'       => [
                            'type'  => 'success'
                        ],
                        'plugin'    => [
                            'type'  => 'danger'
                        ],
                    ],
                ],
            ])
            ->create();
        return parent::successRes($data);
    }

    /**
     * 列表
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-10
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $page = (int)$request->get('page', 1);
        $data = CloudService::list($page)->array();
        if ($data['code'] !== 200) {
            return json($data);
        }
        foreach ($data['data']['data'] as $key => $value) {
            $money = (float)$value['money'];
            // 是否可以购买
            if ($money > 0 && !isset($value['order']['order_no'])) {
                $data['data']['data'][$key]['plugin_status'] = 'buy';
            }
            // 是否可以安装
            if (isset($value['order']['order_no']) && $value['order']['order_no']) {
                $data['data']['data'][$key]['plugin_status'] = 'install';
            }
            // 是否可以更新
            // 是否已安装
            $uninstall = '';
            if ($uninstall) {
                $data['data']['data'][$key]['plugin_status'] = 'uninstall';
            }
            $data['data']['data'][$key]['money'] = $money;
        }
        return json($data);
    }

    /**
     * 获取插件应用信息
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  Request $request
     * @return void
     */
    public function detail(Request $request)
    {
        $id = (int)$request->get('id');
        return CloudService::detail($id);
    }

    /**
     * 购买应用插件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  Request $request
     * @return void
     */
    public function buy(Request $request)
    {
        $id = (int)$request->get('id');
        return CloudService::buyApp($id);
    }

    /**
     * 安装应用插件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  Request $request
     * @return void
     */
    public function install(Request $request)
    {
        $id = (int)$request->get('id');
        $step = (string)$request->get('step');

        // SSE返回
        if ($step === 'file') {
            $response = response();

            $headers['Connection']          = 'keep-alive';
            $headers['Cache-Control']       = 'no-store';
            $headers['Transfer-Encoding']   = 'chunked';
            $headers['X-Accel-Buffering']   = 'no';
            $headers['Content-Type']        = 'text/event-stream';
            $response->withHeaders($headers);

            $data['event'] = 'message';
            $data['data'] = '123456';
            $serverSent = new ServerSentEvents($data);

            $response->withBody($serverSent);

            return $response;
        }















        return parent::success('ok');

        $url = "http://server8.saisaitu.net/1.zip";
        $savePath = base_path('/plugin/') . '1.zip';
        // 获取应用信息
        $response = CloudService::detail($id)->array();
        if ($response['code'] !== 200) {
            return json($response);
        }
        $plugin = $response['data'];

        // 检测文件是否存在
        if (!file_exists(base_path("/plugin/{$plugin['name']}/config/app.php"))) {
            return parent::successRes(['step' => 'file']);
        }


        // 创建图像
        // $im = imagecreatetruecolor(120, 20);
        // $text_color = imagecolorallocate($im, 233, 14, 91);
        // imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);

        // // 开始获取输出
        // ob_start();
        // // 输出图像
        // imagejpeg($im);
        // // 获得图像内容
        // $image = ob_get_clean();

        // 发送图像
        // return response($image)->header('Content-Type', 'image/jpeg');

        // 开始获取输出
        ob_start();
        fopen($url, 'rb');
        // 获得图像内容
        $fileStrame = ob_get_clean();
        // $fileData = file_get_contents($url);
        // file_put_contents($savePath, $fileData);
        print_r($fileStrame);
        return response($fileStrame)->header('Content-Type', 'text/event-stream');
        // return response()->download($url, $savePath);
        // $progress = $this->download($url, $savePath);
        // p($progress);
        // return parent::success('异步测试中...');
        // 文件大小
        // $fileHeaders = get_headers($url, true);
        // if (!isset($fileHeaders['Content-Length'])) {
        //     throw new AppException('获取文件大小失败');
        // }
        // 文件大小（BT）
        // $fileSize = $fileHeaders['Content-Length'];
        // p($progress, '下载进度');
        $response = response();
        // $response = $response->withHeaders([
        //     'Content-Type'      => 'text/event-stream'
        // ]);
        // $response = $response->download($url, $savePath);
        $response = $response->file($url);
        return $response;

        return parent::success('异步测试中...');




        $id = (int)$request->get('id');
        $step = (string)$request->get('step');
        // 获取应用信息
        $response = CloudService::detail($id)->array();
        if ($response['code'] !== 200) {
            return json($response);
        }
        $plugin = $response['data'];
        // 检测文件是否存在
        if (!file_exists(base_path("/plugin/{$plugin['name']}/config/app.php"))) {
            // 下载文件
            Http::getAsync('');
        }
        // 检测数据表安装
        return json(CloudService::install($id, $step)->array());
    }

    /**
     * 下载文件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-24
     * @param  string $file
     * @param  string $filename
     * @return void
     */
    public function download(string $file, string $filePath)
    {
        // 文件已存在
        if (file_exists($filePath)) {
            throw new AppException('文件已存在');
        }
        $fileHeaders = get_headers($file, true);
        if (!isset($fileHeaders['Content-Length'])) {
            throw new AppException('获取文件大小失败');
        }
        // 文件大小（BT）
        $fileSize = $fileHeaders['Content-Length'];
        //设置头部
        // header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        // header('Content-Transfer-Encoding: binary');
        // header('Accept-Ranges: bytes');
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        // header("Content-Length: {$fileSize}");
        // header("Content-Disposition: attachment; filename={$filePath}");
        // 打开远程文件
        $fp = fopen($file, 'rb');
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
                return $stream_size;
            }
            // ob_flush(); // 刷新PHP缓冲区到Web服务器
            // flush(); // 刷新Web服务器缓冲区到浏览器
        }
        fclose($fp);
    }


    /**
     * 更新应用
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  Request $request
     * @return void
     */
    public function update(Request $request)
    {
    }

    /**
     * 卸载应用插件
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-23
     * @param  Request $request
     * @return void
     */
    public function uninstall(Request $request)
    {
    }
}
