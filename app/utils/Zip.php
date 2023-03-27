<?php

namespace app\utils;

use ZipArchive;

class Zip
{
    /**
     * 压缩文件或目录为zip
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-20
     * @param  string      $source_path 要压缩的源文件或文件夹
     * @param  string|null $target_path 保存压缩文件的路径（不填则与源文件同目录，只写目录则自动生成文件名）
     * @return void
     */
    public static function zipCreate(string $source_path, string $target_path = null)
    {
        if ($target_path === null) {
            $target_path = $source_path . '.zip';
        } else if (is_dir($target_path)) {
            $target_path .= '/' . basename($source_path) . '.zip';
        }
        // 验证输出文件路径合法性
        if (file_exists($target_path)) {
            if (is_file($target_path)) {
                unlink($target_path);
            } else if (realpath($target_path) == '/') {
                throw new \Exception('目标路径不能为 "/" 根目录');
            } else {
                throw new \Exception('目标路径不能为已存在目录');
            }
        }

        $zip_resource = new ZipArchive();
        $zip_resource->open($target_path, ZipArchive::CREATE);
        $dir_name = pathInfo($source_path)['basename'];
        if (is_file($source_path)) {
            $zip_resource->addFile($source_path);
        } else {
            $zip_resource->addEmptyDir($dir_name);
            self::addFolderRecursion($zip_resource, $source_path, $dir_name);
        }
        $zip_resource->close();
    }

    /**
     * 递归将文件夹内的文件添加到zip对象
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-20
     * @param  type $zip_resource zip对象
     * @param  string $source_path 要添加的文件夹路径
     * @param  string $local_parent_path zip资源的本地路径（相对zip资源内的路径）
     * @return void
     */
    public static function addFolderRecursion(&$zip_resource, string $source_path, string $local_parent_path = null)
    {
        $handle = opendir($source_path);
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $file_path = "$source_path/$f";
                $local_path = trim("$local_parent_path/$f", '/'); // 相对zip资源内的路径
                if (is_file($file_path)) {
                    $zip_resource->addFile($file_path, $local_path);
                } else {
                    $zip_resource->addEmptyDir($local_path);
                    self::addFolderRecursion($zip_resource, $file_path, $local_path);
                }
            }
        }
    }

    /**
     * zip解压缩
     * 注：ZipArchive::extractTo() 有异常，会丢失文件和文件错位，中文文件夹不兼容（有人说是编码问题）
     *
     * @Author 贵州猿创科技有限公司
     * @Email 416716328@qq.com
     * @DateTime 2023-03-20
     * @param  string    $source_path 要解压的zip文件路径
     * @param  string    $target_path 导出到的目标文件夹，默认null时输出到zip文件所在目录
     * @param  boolean $force_cover 是否强制覆盖（默认false，文件已存在时会报错）
     * @return void
     */
    public static function zipExport(string $source_path, string $target_path = null, bool $force_cover = false)
    {
        if ($target_path === null) {
            $target_path = dirname($source_path);
        }
        if (!file_exists($target_path)) {
            throw new \Exception('目标路径"' . $target_path . '"不存在');
        } else if (!is_dir($target_path)) {
            throw new \Exception('目标路径"' . $target_path . '"必须为目录');
        }
        $source_path = realpath($source_path);
        $target_path = realpath($target_path);

        $zip_resource = new ZipArchive();
        if ($zip_resource->open($source_path) === true) {
            for ($i = 0; $i < $zip_resource->numFiles; $i++) {
                $index_stat = $zip_resource->statIndex($i);
                $index_file_name = $index_stat['name'];
                $out_path = "$target_path/$index_file_name";

                if (file_exists($out_path) && !$force_cover) {
                    throw new \Exception('不允许覆盖，如需强制覆盖请将第3个参数设为false');
                }

                if ($index_stat['crc'] != 0) {
                    $zip_copy_path = "zip://$source_path#$index_file_name";
                    copy($zip_copy_path, $out_path);
                } else {
                    @mkdir($out_path, 0777);
                }
            }
        } else {
            throw new \Exception('zip文件打开失败');
        }
        $zip_resource->close();
    }
}
