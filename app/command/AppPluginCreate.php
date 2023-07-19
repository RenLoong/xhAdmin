<?php

namespace app\common\command;

use app\model\StoreApp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

class AppPluginCreate extends Command
{
    protected static $defaultName = 'yc-plugin:create';
    protected static $defaultDescription = 'App Plugin Create';

    # 后台类型：默认2
    private $adminType = 2;
    private $adminTypeArr = [
        1           => '官方后台',
        2           => '自主实现',
    ];
    private $adminTypeText = null;

    /**
     * 创建应用插件配置
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function configure()
    {
        # 拼接后台类型数组
        $adminTypeText      = '';
        foreach ($this->adminTypeArr as $key => $value) {
            $adminTypeText .= "{$key}.{$value} ";
        }
        $this->adminTypeText = $adminTypeText;
    }

    /**
     * 执行命令
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        # 团队标识
        $teamName = $this->getTeamName($input, $output);
        # 插件名称
        $pluginName = $this->getPluginName($input, $output);
        # 插件名称首字母转大写
        $pluginNameUc = ucfirst($pluginName);
        # 团队+插件名称
        $teamPlugin = "{$teamName}{$pluginNameUc}";
        # 验证插件是否存在
        if (is_dir(base_path("/plugin/{$teamPlugin}"))) {
            $output->writeln('<error>该应用插件已创建</error>');
            return self::FAILURE;
        }
        # 后台类型：1.官方后台 2.自主实现
        $this->adminType = $this->getAdminType($input, $output);

        # 收集数据完成，提示用户确认
        $output->writeln("<fg=red>");
        $output->writeln("------插件创建信息------");
        $output->writeln("团队标识：{$teamName}");
        $output->writeln("插件名称：{$pluginName}");
        $output->writeln("插件标识：{$teamPlugin}");
        $output->writeln("后台类型：{$this->adminTypeArr[$this->adminType]}");
        $output->writeln("------请确认创建信息------");
        $output->writeln("</>");
        $confirm = $this->confirmCreate($input, $output);
        if ($confirm !== 'YES') {
            $output->writeln('<error>应用插件创建取消...</error>');
            return self::FAILURE;
        }
        # 执行创建应用文件
        $this->createPluginFiles($teamPlugin);
        # 创建应用安装文件
        $this->createApiFiles(base_path("/plugin/{$teamPlugin}/api"), $teamPlugin);
        # 创建成功
        $output->writeln('<fg=green>示例应用插件创建成功...</>');
        # 创建开发者测试平台
        self::createStoreApp($teamPlugin);
        $output->writeln('<fg=red>开发者测试平台创建成功，请重启框架后访问...</>');
        # 创建成功
        return self::SUCCESS;
    }

    /**
     * 创建开发者测试平台
     * @param string $name
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function createStoreApp(string $name)
    {
        $model = new StoreApp;
        $model->store_id = 0;
        $model->platform_id = 0;
        $model->title = '开发者测试平台';
        $model->name = $name;
        if (!$model->save()) {
            console_log("创建 {$name} 开发者测试平台失败...");
            return;
        }
        console_log("创建 {$name} 开发者测试平台成功...");
    }

    /**
     * 创建应用文件
     * @param string $pluginName
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function createPluginFiles(string $pluginName)
    {
        $base_path = base_path();
        # 创建目录
        if ($this->adminType == 1) {
            $this->mkdir("$base_path/plugin/$pluginName/app/admin/controller", 0777, true);
        }
        $this->mkdir("$base_path/plugin/$pluginName/app/controller", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/app/model", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/app/validate", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/app/middleware", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/app/view/index", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/packages", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/config", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/public", 0777, true);
        $this->mkdir("$base_path/plugin/$pluginName/api", 0777, true);
        # 创建函数文件
        $this->createFunctionsFile("$base_path/plugin/$pluginName/app/functions.php");
        # 创建控制器
        $this->createControllers($base_path, $pluginName);
        # 创建默认视图
        $this->createViewFile("$base_path/plugin/$pluginName/app/view/index/index.html");
        # 复制配置文件
        $this->CreateConfigFiles("$base_path/plugin/$pluginName/config", $pluginName);
        # 创建远程默认组件
        $this->createRemoteFiles("$base_path/plugin/$pluginName/public/remote", $pluginName);
        # 创建初始版本文件
        $this->CreateVersionFiles("$base_path/plugin/$pluginName", $pluginName);
    }

    /**
     * 创建应用安装
     * @param string $base
     * @param string $pluginName
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function createApiFiles(string $base,string $pluginName)
    {
        # 创建应用安装文件
        $content = file_get_contents(app_path('/command/appPlugin/install/install.txt'));
        $content = str_replace('{PLUGIN_NAME}', $pluginName, $content);
        file_put_contents("{$base}/Install.php", $content);
        console_log("创建应用安装接口文件成功 {$base}/Install.php");

        # 创建应用
        $content = file_get_contents(app_path('/command/appPlugin/install/created.txt'));
        $content = str_replace('{PLUGIN_NAME}', $pluginName, $content);
        file_put_contents("{$base}/Created.php", $content);
        console_log("创建应用管理员接口文件成功 {$base}/Created.php");

        # 应用登录
        $content = file_get_contents(app_path('/command/appPlugin/install/login.txt'));
        $content = str_replace('{PLUGIN_NAME}', $pluginName, $content);
        file_put_contents("{$base}/Login.php", $content);
        console_log("创建应用登录接口文件成功 {$base}/Login.php");
    }

    /**
     * 创建远程组件
     * @param mixed $base_path
     * @param mixed $pluginName
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function createRemoteFiles($base_path, $pluginName)
    {
        # 检测目录并创建
        if (!is_dir($base_path)) {
            mkdir($base_path, 0777, true);
        }
        # 创建默认欢迎页面
        $data = <<<EOF
        <template>
            <div>
                这是 {$pluginName} 的欢迎页面
            </div>
        </template>

        <script>
        export default {
        }
        </script>

        <style>
        </style>
        EOF;
        file_put_contents($base_path . '/welcome.vue', $data);
        console_log("创建默认欢迎页面 {$base_path}/welcome.vue");

        # 创建头部工具栏页面
        $data = <<<EOF
        <template>
            <div>
                这是 {$pluginName} 的头部工具栏
            </div>
        </template>

        <script>
        export default {
        }
        </script>

        <style>
        </style>
        EOF;
        file_put_contents($base_path . '/header-toolbar.vue', $data);
        console_log("创建头部工具栏页面 {$base_path}/header-toolbar.vue");
    }

    /**
     * 
     * @param mixed $base_path
     * @param mixed $pluginName
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private static function CreateVersionFiles($base_path, $pluginName)
    {
        $data = <<<EOF
        {
            "version" : 1,
            "version_name" : "1.0.0"
        }
        EOF;
        file_put_contents($base_path . '/version.json', $data);
        console_log("创建初始版本文件 {$base_path}/version.json");
    }

    /**
     * 创建配置文件
     * @param string $path
     * @param string $pluginName
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function CreateConfigFiles(string $path,string $pluginName)
    {
        console_log("创建配置文件 {$path}/app.php");
        $content = <<<EOF
        <?php

        use support\\Request;

        return [
            'debug' => true,
            'controller_suffix' => 'Controller',
            'controller_reuse' => false,
        ];
        EOF;
        file_put_contents("$path/app.php", $content);

        console_log("创建配置文件 {$path}/autoload.php");
        $content = <<<EOF
        <?php

        return [
            'files' => [
                base_path() . '/plugin/$pluginName/app/functions.php',
            ]
        ];
        EOF;
        file_put_contents("$path/autoload.php", $content);
        
        console_log("创建配置文件 {$path}/container.php");
        $content = <<<EOF
        <?php

        return new Webman\\Container;
        EOF;
        file_put_contents("$path/container.php", $content);
        
        console_log("创建配置文件 {$path}/database.php");
        $content = <<<EOF
        <?php

        return [];
        EOF;
        file_put_contents("$path/database.php", $content);
        
        console_log("创建配置文件 {$path}/exception.php");
        $content = <<<EOF
        <?php

        return [
            '' => \\app\common\\exception\\Handler::class,
        ];
        EOF;
        file_put_contents("$path/exception.php", $content);

        console_log("创建配置文件 {$path}/log.php");
        $content = <<<EOF
        <?php

        return [
            'default' => [
                'handlers' => [
                    [
                        'class' => Monolog\\Handler\\RotatingFileHandler::class,
                        'constructor' => [
                            runtime_path() . '/logs/$pluginName.log',
                            7,
                            Monolog\\Logger::DEBUG,
                        ],
                        'formatter' => [
                            'class' => Monolog\\Formatter\\LineFormatter::class,
                            'constructor' => [null, 'Y-m-d H:i:s', true],
                        ],
                    ]
                ],
            ],
        ];
        EOF;
        file_put_contents("$path/log.php", $content);
        
        console_log("创建中间件配置文件 {$path}/middleware.php");
        $content = <<<EOF
        <?php

        return [
            '' => [
                \app\common\middleware\PluginsMiddleware::class
            ]
        ];
        EOF;
        file_put_contents("$path/middleware.php", $content);

        console_log("创建配置文件 {$path}/process.php");
        $content = <<<EOF
        <?php

        return [];
        EOF;
        file_put_contents("$path/process.php", $content);

        console_log("创建redis配置文件 {$path}/redis.php");
        $content = <<<EOF
        <?php
        return [
            'default' => [
                'host' => '127.0.0.1',
                'password' => null,
                'port' => 6379,
                'database' => 0,
            ],
        ];
        EOF;
        file_put_contents("$path/redis.php", $content);

        console_log("创建路由配置文件 {$path}/route.php");
        $content = <<<EOF
        <?php

        use Webman\\Route;
        EOF;
        file_put_contents("$path/route.php", $content);

        console_log("创建静态资源中间件配置文件 {$path}/static.php");
        $content = <<<EOF
        <?php

        return [
            'enable' => true,
            'middleware' => [],    // Static file Middleware
        ];
        EOF;
        file_put_contents("$path/static.php", $content);

        console_log("创建多语言配置文件 {$path}/translation.php");
        $content = <<<EOF
        <?php

        return [
            // Default language
            'locale' => 'zh_CN',
            // Fallback language
            'fallback_locale' => ['zh_CN', 'en'],
            // Folder where language files are stored
            'path' => base_path('/resource/translations'),
        ];
        EOF;
        file_put_contents("$path/translation.php", $content);

        console_log("创建配置文件：{$path}/view.php");
        $content = <<<EOF
        <?php

        use support\\view\\Raw;
        use support\\view\\Twig;
        use support\\view\\Blade;
        use support\\view\\ThinkPHP;

        return [
            'handler' => Raw::class
        ];
        EOF;
        file_put_contents("$path/view.php", $content);

        console_log("创建TP-ORM配置文件 {$path}/thinkorm.php");
        $content = <<<EOF
        <?php

        return [];
        EOF;
        file_put_contents("$path/thinkorm.php", $content);

        # 创建官方后台配置文件
        if ($this->adminType == 1) {
            # 创建配置菜单
            $content = file_get_contents(app_path('/command/appPlugin/config/menu.txt'));
            $content = str_replace("{PLUGIN_NAME}", $pluginName, $content);
            file_put_contents("{$path}/menu.php", $content);
            console_log("创建菜单配置文件 {$path}/menu.php");

            # 创建菜单JSON文件
            $pluginRoot = dirname($path);
            $content = file_get_contents(app_path('/command/appPlugin/config/menu.json'));
            $str1       = [
                "{PLUGIN_NAME}"
            ];
            $str2       = [
                $pluginName
            ];
            $content    = str_replace($str1, $str2, $content);
            file_put_contents("{$pluginRoot}/menu.json", $content);

            # 创建后台配置
            $content = <<<EOF
            <?php
    
            return [
                'admin',
            ];
            EOF;
            file_put_contents("{$path}/admin.php", $content);
            console_log("创建配置文件 {$path}/admin.php");
        }
    }

    /**
     * 创建视图文件
     * @param string $path
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function createViewFile(string $path)
    {
        echo "创建应用默认视图 {$path}\r\n";
        $content = <<<EOF
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="shortcut icon" href="/favicon.ico"/>
            <title>Hello，KFAdmin Plugin</title>

        </head>
        <body>
        hello <?=htmlspecialchars(\$name)?>
        </body>
        </html>
        EOF;
        file_put_contents($path, $content);
    }

    /**
     * 创建控制器
     * @param string $base_path
     * @param string $pluginName
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function createControllers(string $base_path, string $pluginName)
    {
        $pluginPath = "$base_path/plugin/$pluginName";
        $pluginAppPath = "$pluginPath/app";
        $defaultPath = "$pluginAppPath/controller";
        # 创建默认控制器
        $this->createDefaultController("{$defaultPath}/IndexController.php", $pluginName);
        # 创建官方后台登录控制器
        if ($this->adminType == 1) {
            $this->copyPublicsController("{$pluginAppPath}/admin/controller/PublicsController.php", $pluginName);
        }
    }

    /**
     * 创建官方默认登录控制器
     * @param string $path
     * @param string $name
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function copyPublicsController(string $path, string $name)
    {
        echo "创建官方后台登录控制器 {$path}\r\n";
        $content = file_get_contents(app_path('/command/appPlugin/PublicsController.txt'));
        $content = str_replace('{PLUGIN_NAME}', $name, $content);
        file_put_contents($path, $content);
    }

    /**
     * 创建默认控制器文件
     * @param string $path
     * @param string $name
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function createDefaultController(string $path, string $name)
    {
        echo "创建应用默认控制器 {$path}\r\n";
        $content = <<<EOF
        <?php

        namespace plugin\\$name\\app\\controller;

        use support\\Request;
        use app\\BaseController;

        class IndexController extends BaseController
        {
            /**
             * 默认控制器文件
             * @param \\support\\Request \$request
             * @return \\support\\Response
             * @author 贵州猿创科技有限公司
             * @copyright 贵州猿创科技有限公司
             */
            public function index(Request \$request)
            {
                return view('index/index', ['name' => '$name']);
            }

        }
        EOF;
        file_put_contents($path, $content);
    }

    /**
     * 创建默认函数文件
     * @param string $file
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function createFunctionsFile(string $file)
    {
        echo "创建应用函数库 {$file}\r\n";
        $content = <<<EOF
        <?php
        /**
         * Here is your custom functions.
         */
        EOF;
        file_put_contents($file, $content);
    }

    
    /**
     * 创建目录
     * @param string $path
     * @param mixed $permissions
     * @param bool $recursive
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function mkdir(string $path,$permissions = 0777,bool $recursive = false)
    {
        if (is_dir($path)) {
            return;
        }
        echo "创建目录 {$path}\r\n";
        mkdir($path, 0777, true);
    }

    /**
     * 是否确认创建插件
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function confirmCreate(InputInterface $input, OutputInterface $output)
    {
        $confirm = $this->waitInput($input, $output, "<fg=yellow>是否确认创建应用插件？[yes/no]</>");
        if (!$confirm) {
            $output->writeln('<error>请选择是否创建插件</error>');
            return $this->confirmCreate($input, $output);
        }
        $confirm = strtoupper($confirm);
        if (!in_array($confirm, ['YES', 'NO'])) {
            $output->writeln('<error>请输入正确的选择</error>');
            return $this->confirmCreate($input, $output);
        }
        return $confirm;
    }

    /**
     * 获取后台类型
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function getAdminType(InputInterface $input, OutputInterface $output)
    {
        $adminType = (int)$this->waitInput($input, $output, "后台类型：<fg=yellow>{$this->adminTypeText}</>");
        if (!$adminType) {
            $output->writeln('<error>请选择后台类型</error>');
            return $this->getAdminType($input, $output);
        }
        if (!in_array($adminType, [1, 2])) {
            $output->writeln('<error>请选择正确的后台类型</error>');
            return $this->getAdminType($input, $output);
        }
        return $adminType;
    }

    /**
     * 获取插件名称
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function getPluginName(InputInterface $input, OutputInterface $output)
    {
        $pluginName = $this->waitInput($input, $output, '请输入插件名称');
        if (!$pluginName) {
            $output->writeln('<error>请输入插件名称</error>');
            return $this->getPluginName($input, $output);
        }
        if (preg_match('/[^a-zA-Z0-9]/', $pluginName)) {
            $output->writeln('<error>插件名称只能是字母和数字</error>');
            return $this->getPluginName($input, $output);
        }
        return $pluginName;
    }

    /**
     * 获取团队标识
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function getTeamName(InputInterface $input, OutputInterface $output)
    {
        $teamName = $this->waitInput($input, $output, '请输入团队标识');
        if (!$teamName) {
            $output->writeln('<error>请输入团队名称</error>');
            return $this->getTeamName($input, $output);
        }
        if (preg_match('/[^a-zA-Z0-9]/', $teamName)) {
            $output->writeln('<error>团队名称只能是字母和数字</error>');
            return $this->getTeamName($input, $output);
        }
        return $teamName;
    }

    /**
     * 等待用户输入
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $message
     * @return string|null
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    private function waitInput(InputInterface $input, OutputInterface $output, string $message): string|null
    {
        $helper   = $this->getHelper('question');
        $question = new Question("{$message}: ");
        return $helper->ask($input, $output, $question);
    }
}