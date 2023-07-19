<?php

namespace app\common\command;

use app\admin\utils\ComposerMgr;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class ComposerApp extends Command
{
    protected static $defaultName = 'yc-composer:app';
    protected static $defaultDescription = 'Composer validate';
    # composer通用命令
    private static $composerCommand = 'export COMPOSER_HOME=/www/server/php/80/bin;COMPOSER_ALLOW_SUPERUSER=1;';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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
        # 更新应用composer
        ComposerMgr::composerMergePlugin($name);
        return self::SUCCESS;
    }

    /**
     * 扫描指定目录下的composer文件
     *
     * @param  string $dir
     * @return array
     */
    public function scanPluginComposerJson(string $composerPath) {
        // 获取所有的plugin/*/packages/composer.json文件路径
        $files = glob($composerPath);
        $names = [];
        // 读取每个json文件并解析出name字段
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $json = json_decode($content, true);
            if (isset($json['name'])) {
                $names[] = $json['name'];
            }
        }
        // 返回name字段列表
        return $names;
    }
}
