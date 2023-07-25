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
        $sqlFiles = scandir($sqlDir);
        $extension = '.sql';
        $data      = [];
        foreach ($sqlFiles as $file) {
            if (strpos($file, $extension) !== false) {
                $data[] = $file;
            }
        }
        if (empty($data)) {
            return [];
        }
        $dataList = [
            'files'     => [],
            'sql'       => [],
        ];
        foreach ($data as $value) {
            $item = file_get_contents("{$sqlDir}/{$value}");
            if (!empty($item)) {
                $keyName = str_replace('.sql','',$value);
                $dataList['files'][] = $keyName;
                $item = DbMgr::removeComments($item);
                $item = DbMgr::splitSqlFile($item,';');
                $dataList['sql'] = array_merge($dataList['sql'],$item);
            }
        }
        return $dataList;
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
        if (empty($data['files']) || empty($data['sql'])) {
            return;
        }
        $prefix = config('database.connections.mysql.prefix');
        $str    = ['`php_', '`yc_'];
        foreach ($data['sql'] as $sql) {
            # 替换为真实SQL
            $sql = str_replace($str, "`{$prefix}", $sql);
            try {
                DbMgr::instance()->statement($sql);
            } catch (\Throwable $e) {
                Log::error("执行更新SQL错误：{$e->getMessage()}");
                continue;
            }
        }
        # 清空目录
        foreach ($data['files'] as $file) {
            $filePath = base_path("/update/{$file}.sql");
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $updateDir = base_path('/update');
        if (is_dir($updateDir)) {
            rmdir($updateDir);
        }
    }
}