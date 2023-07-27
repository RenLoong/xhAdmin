<?php
namespace app\common\service;
use app\common\manager\DbMgr;
use support\Log;
use support\Request;

/**
 * 执行数据同步更新
 * @author 贵州猿创科技有限公司
 * @copyright (c) 贵州猿创科技有限公司
 */
class UpdateDataService extends SystemUpdateService
{
    /**
     * 客户端版本号
     * @var int
     */
    protected $clientVersion = null;

    /**
     * 构造函数
     * @param \support\Request $request
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function __construct(Request $request,$clientVersion)
    {
        $this->clientVersion = $clientVersion;
        parent::__construct($request);
    }

    /**
     * 前置更新
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function beforeUpdate()
    {
        # sql目录
        $sqlDir = base_path('/update');
        if (!is_dir($sqlDir)) {
            return [];
        }
        # 获取sql文件
        $sqlFiles = array_values(array_diff(scandir($sqlDir), ['.', '..']));
        $extension = '.sql';
        $data      = [];
        foreach ($sqlFiles as $file) {
            if (!strpos($file, $extension)) {
                continue;
            }
            $sqlContent = file_get_contents("{$sqlDir}/{$file}");
            if (empty($sqlContent) && file_exists("{$sqlDir}/{$file}")) {
                unlink("{$sqlDir}/{$file}");
                continue;
            }
            $data[] = [
                'file' => $file,
                'sql'  => $sqlContent,
            ];
        }
        return $data;
    }

    /**
     * 后置更新
     * @param mixed $data
     * @return void
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     */
    public function update($data)
    {
        $prefix     = config('database.connections.mysql.prefix');
        $prefixs    = ['`php_', '`yc_'];
        try {
            # 连接原生PDO
            $pdo = DbMgr::instance()->getPdo();
            foreach ($data as $value) {
                if (empty($value['file'])) {
                    continue;
                }
                if (empty($value['sql'])) {
                    continue;
                }
                # 替换前缀
                $sql = str_replace($prefixs, "`{$prefix}", $value['sql']);
                # 执行SQL
                $pdo->exec($sql);
                # 执行成功后删除文件
                // $filePath = base_path("/update/{$value['file']}");
                // file_exists($filePath) && unlink($filePath);
            }
        } catch (\Throwable $e) {
            Log::error("执行SAAS系统 - 更新SQL错误：{$e->getMessage()}");
        }
    }
}