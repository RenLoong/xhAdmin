<?php
/**
 * 目录处理类
 * @package     tools_class
 * @author      后盾向军 <houdunwangxj@gmail.com>
 */
final class Dir{

    /**
     * @param string $dir_name 目录名
     * @return mixed|string
     */
    static public function dirPath($dir_name)
    {
        $dirname = str_ireplace("\\", "/", $dir_name);
        return substr($dirname, "-1") == "/" ? $dirname : $dirname . "/";
    }

    /**
     * 获得扩展名
     * @param string $file 文件名
     * @return string
     */
    static public function getExt($file)
    {
        return strtolower(substr(strrchr($file, "."), 1));
    }

    /**
     * 遍历目录内容
     * @param string $dirName 目录名
     * @param string $exts 读取的文件扩展名
     * @param int $son 是否显示子目录
     * @param array $list
     * @return array
     */
    static public function tree($dirName = null, $exts = '', $son = 0,$list = array() )
    {

        if (is_null($dirName)) $dirName = '.';
        $dirPath = self::dirPath($dirName);
        if (is_array($exts))
            $exts = implode("|", $exts);
        foreach (glob($dirPath . '*') as $v) {
            if (is_dir($v) || !$exts || preg_match("/\.($exts)/i", $v)) {
                $path = str_replace("\\", "/", realpath($v)) . (is_dir($v) ? '/' : '');
                $list[]=[
                    'type'=>filetype($v),
                    'filename'=>basename($v),
                    'path'=>$path,
                    'spath'=>ltrim(str_replace(dirname($_SERVER['SCRIPT_FILENAME']),'',$path),'/'),
                    'filemtime'=>filemtime($v),
                    'fileatime'=>fileatime($v),
                    'size'=>is_file($v) ? filesize($v) : self::get_dir_size($v),
                    'iswrite'=>is_writeable($v) ? 1 : 0,
                    'isread'=>is_readable($v) ? 1 : 0,
                    'data'=>$son&&is_dir($v)?self::tree($v, $exts, $son = 1, $list):[]
                ];
            }
        }
        return $list;
    }

    /**
     * 遍历目录内容【时间控制】
     * @param string $dirName 目录名
     * @param string $exts 读取的文件扩展名
     * @param int $son 是否显示子目录
     * @param array $list
     * @return array
     */
    static public function treeTime($dirName = null,$startDate=0 , $endDate=0, $exts = '', $son = 0,$list = array() )
    {

        if (is_null($dirName)) $dirName = '.';
        $dirPath = self::dirPath($dirName);
        if (is_array($exts))
            $exts = implode("|", $exts);
        foreach (glob($dirPath . '*') as $v) {
            if(filemtime($v) > $startDate && filemtime($v) < $endDate){
                if (is_dir($v) || !$exts || preg_match("/\.($exts)/i", $v)) {
                        /*$list [$id] ['type'] = filetype($v);
                        $list [$id] ['filename'] = basename($v);
                        $path = str_replace("\\", "/", realpath($v)) . (is_dir($v) ? '/' : '');
                        $list [$id] ['path']=$path;
                        $list [$id] ['spath']=ltrim(str_replace(dirname($_SERVER['SCRIPT_FILENAME']),'',$path),'/');
                        $list [$id] ['filemtime'] = filemtime($v);
                        $list [$id] ['fileatime'] = fileatime($v);
                        $list [$id] ['size'] = is_file($v) ? filesize($v) : self::get_dir_size($v);
                        $list [$id] ['iswrite'] = is_writeable($v) ? 1 : 0;
                        $list [$id] ['isread'] = is_readable($v) ? 1 : 0;*/
                        $path = str_replace("\\", "/", realpath($v)) . (is_dir($v) ? '/' : '');
                        $list[]=[
                            'type'=>filetype($v),
                            'filename'=>basename($v),
                            'path'=>$path,
                            'spath'=>ltrim(str_replace(dirname($_SERVER['SCRIPT_FILENAME']),'',$path),'/'),
                            'filemtime'=>filemtime($v),
                            'fileatime'=>fileatime($v),
                            'size'=>is_file($v) ? filesize($v) : self::get_dir_size($v),
                            'iswrite'=>is_writeable($v) ? 1 : 0,
                            'isread'=>is_readable($v) ? 1 : 0,
                            'data'=>$son&&is_dir($v)?self::tree($v, $exts, $son = 1, $list):[]
                        ];
                }
            }
        }
        return $list;
    }

    static public function get_dir_size($f)
    {
        $s = 0;
        foreach (glob($f . '/*') as $v) {
            $s += is_file($v) ? filesize($v) : self::get_dir_size($v);
        }
        return $s;
    }

    /**
     * 只显示目录树
     * @param null $dirName 目录名
     * @param int $son
     * @param int $pid 父目录ID
     * @param array $dirs 目录列表
     * @return array
     */
    static public function treeDir($dirName = null, $son = 0, $pid = 0, $dirs = array())
    {
        if (!$dirName) $dirName = '.';
        static $id = 0;
        $dirPath = self::dirPath($dirName);
        foreach (glob($dirPath . "*") as $v) {
            if (is_dir($v)) {
                $id++;
                $dirs [$id] = array("id" => $id, 'pid' => $pid, "dirname" => basename($v), "dirpath" => $v);
                if ($son) {
                    $dirs = self::treeDir($v, $son, $id, $dirs);
                }
            }
        }
        return $dirs;
    }

    /**
     * 删除目录及文件，支持多层删除目录
     * @param string $dirName 目录名
     * @return bool
     */
    static public function del($dirName)
    {
        if (is_file($dirName)) {
            unlink($dirName);
            return true;
        }
        $dirPath = self::dirPath($dirName);
        if (!is_dir($dirPath)) return true;
        foreach (glob($dirPath . "*") as $v) {
            is_dir($v) ? self::del($v) : unlink($v);
        }
        return @rmdir($dirName);
    }

    /**
     * 批量创建目录
     * @param $dirName 目录名数组
     * @param int $auth 权限
     * @return bool
     */
    static public function create($dirName, $auth = 0755)
    {
        $dirPath = self::dirPath($dirName);
        if (is_dir($dirPath))
            return true;
        $dirs = explode('/', $dirPath);
        $dir = '';
        foreach ($dirs as $v) {
            $dir .= $v . '/';
            if (is_dir($dir))
                continue;
            mkdir($dir, $auth);
        }
        return is_dir($dirPath);
    }
}