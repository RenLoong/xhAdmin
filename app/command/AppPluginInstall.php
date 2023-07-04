<?php

namespace app\command;

use app\model\StoreApp;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

class AppPluginInstall extends Command
{
    protected static $defaultName = 'yc-plugin:install';
    protected static $defaultDescription = 'App Plugin Install';

    /**
     * 创建应用插件配置
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
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
        $name = $input->getArgument('name');
        if (!$name) {
            $output->writeln('请输入应用名称');
            return self::FAILURE;
        }
        $pluginApp = base_path("/plugin/{$name}");
        if (!is_dir($pluginApp)) {
            $output->writeln("该应用不存在");
            return self::FAILURE;
        }
        $model = new StoreApp;
        $model->store_id = 0;
        $model->platform_id = 0;
        $model->title = '开发者测试平台';
        $model->name = $name;
        if (!$model->save()) {
            $output->writeln("创建 {$name} 开发者测试平台失败");
            return self::FAILURE;
        }
        $output->writeln("创建 {$name} 开发者测试平台成功");
        return self::SUCCESS;
    }
}