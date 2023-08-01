<?php

use app\common\manager\StoreAppMgr;
use app\common\service\UploadService;

/**
 * 获取映射模块名称
 * @param string $moduleName
 * @return string
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-30
 */
function getModule(string $moduleName): string
{
    $modules = config('admin');
    if (isset($modules[$moduleName])) {
        $moduleName = $modules[$moduleName];
    }
    return $moduleName;
}
/**
 * 验证器（支持场景验证）
 * @param mixed $validate
 * @param array $data
 * @param string $scene
 * @throws Exception
 * @return bool
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-30
 */
function hpValidate($validate, array $data, string $scene = ''): bool
{
    // 实例类
    $class = new $validate;
    // 场景验证
    if ($scene) {
        $class->scene($scene);
    }
    $result = $class->check($data);
    if (!$result) {
        throw new Exception((string) $class->getError());
    }
    return true;
}

/**
 * 读取配置项
 * @param mixed $key 系统KEY
 * @param mixed $group_name 分组标识
 * @param mixed $appid 应用项目ID
 * @return string|array
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 */
function getHpConfig($key = '', $group_name = '', $appid = 0): string|array
{
    $model = new \app\common\model\SystemConfig;
    $where   = [];
    if ($appid) {
        $appModel = StoreAppMgr::detail(['id' => $appid]);
        if ($appModel) {
            $where[] = ['store_id', '=', $appModel['store_id']];
            $where[] = ['saas_appid', '=', $appModel['saas_appid']];
        }
    }
    if (empty($where)) {
        $where = [
            ['store_id', '=', null],
            ['saas_appid', '=', null]
        ];
    }
    if ($group_name) {
        $where[] = ['group_name', '=', $group_name];
    }
    $orderBy = [
        'sort'      => 'asc',
        'id'        => 'asc'
    ];
    if ($key) {
        if (is_array($key)) {
            $where[] = ['name', 'in', $key];
        } else {
            $where[] = ['name', '=', $key];
        }
        $model = $model->where($where)->order($orderBy)
            ->select();
    } else {
        $model = $model
            ->where($where)
            ->order($orderBy)
            ->select();
    }
    $data = [];
    foreach ($model as $value) {
        if ($value['component'] == 'uploadify') {
            $files = explode(',', $value['value']);
            $_list = [];
            foreach ($files as $k => $v) {
                $_list[$k] = UploadService::url((string) $v);
            }
            $data[$value['name']] = $_list;
        } else {
            // 其他选项
            $data[$value['name']] = $value['value'];
        }
    }
    if ($key && !is_array($key)) {
        return $data[$key] ?? '';
    }
    return $data;
}

/**
 * 友好时间显示
 * @param int $time
 * @return bool|string
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 */
function friend_date(int $time)
{
    if (!$time)
        return false;
    $fdate = '';
    $d     = time() - intval($time);
    $ld    = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md    = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd   = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd    = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd    = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td    = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd   = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日 H:i', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}
/**
 * 生成6位随机数
 * @param int $len
 * @return string
 * @author 贵州猿创科技有限公司
 * @copyright 贵州猿创科技有限公司
 */
function get_random(int $len = 6)
{
    $unique_no = substr(base_convert(md5(uniqid(md5(microtime(true)), true)), 16, 10), 0, $len);
    return $unique_no;
}
/**
 * XML转数组
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 * @param  string $xml
 * @return array
 */
function xmlToArr(string $xml): array
{
    //将xml转化为json格式
    $jsonxml = json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
    //转成数组
    $result = json_decode($jsonxml, true);

    // 返回数组
    return $result;
}

/**
 * 根据大小返回标准单位 KB  MB GB等
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 * @param  integer $size
 * @param  integer $decimals
 * @return string
 */
function get_size(int $size, int $decimals = 2): string
{
    switch (true) {
        case $size >= pow(1024, 3):
            return round($size / pow(1024, 3), $decimals) . " GB";
        case $size >= pow(1024, 2):
            return round($size / pow(1024, 2), $decimals) . " MB";
        case $size >= pow(1024, 1):
            return round($size / pow(1024, 1), $decimals) . " KB";
        default:
            return $size . 'B';
    }
}
/**
 * 下划线转驼峰
 *
 * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
 * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
 * @param string $uncamelized_words
 * @param string $separator
 * @return string
 */
function camelize(string $uncamelized_words, $separator = '_'): string
{
    $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
}
/**
 * 驼峰命名转下划线命名
 *
 * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
 * @param string $camelCaps
 * @param string $separator
 * @return string
 */
function uncamelize(string $camelCaps, $separator = '_'): string
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

/**
 * 当前登录管理员id
 * @param string $key
 * @return mixed
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-03
 */
function hp_admin_id(string $key)
{
    return session("{$key}.id");
}

/**
 * 当前管理员
 * @param string $key
 * @param mixed $fields
 * @return mixed
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-03
 */
function hp_admin(string $key, $fields = null)
{
    if (!$admin = session($key)) {
        return '';
    }
    if ($fields === null) {
        return $admin;
    }
    if (is_array($fields)) {
        $results = [];
        foreach ($fields as $field) {
            $results[$field] = $admin[$field] ?? null;
        }
        return $results;
    }
    return $admin[$fields] ?? null;
}

/**
 * 对查询结果集进行排序
 * asc正向排序 desc逆向排序 nat自然排序
 * @param mixed $list 查询结果
 * @param mixed $field 排序的字段名
 * @param mixed $sortby 排序类型
 * @return array|bool
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-05-03
 */
function list_sort_by(array $list, string $field, $sortby = 'asc')
{
    if (is_array($list)) {
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 合并数组
 *
 * @param array $oldArr
 * @param array $newArr
 * @return array
 */
function _array_merge(array $oldArr, array $newArr): array
{
    $data = array_merge($oldArr, $newArr);
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = array_merge(
                isset($oldArr[$key]) ? $oldArr[$key] : [],
                isset($newArr[$key]) ? $newArr[$key] : []
            );
        }
    }
    return $data;
}
/**
 * 向URL追加参数并返回
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-02-28
 * @param  string $uri
 * @param  array  $data
 * @return string
 */
function uriPush(string $uri, array $data): string
{
    $url    = parse_url($uri);
    $params = http_build_query($data);
    if (isset($url['query'])) {
        $uri = "{$uri}&{$params}";
    } else {
        $uri = "{$uri}?{$params}";
    }
    return $uri;
}
/**
 * 输出日志到终端（仅调试模式下有效）
 * @param mixed $str
 * @param string $remarks
 * @param bool $type
 * @return void
 * @copyright 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-04-29
 */
function p($str, string $remarks = '日志：', bool $type = false)
{
    if (config('app.debug')) {
        $currentDate = date('Y-m-d');
        echo "{$currentDate}-----{$remarks}";
        echo PHP_EOL;
        if ($type) {
            var_dump($str);
        } else {
            print_r($str);
        }
        echo PHP_EOL;
    }
}

/**
 * 输出日志到控制台
 *
 * @param  string $message
 * @return void
 */
function console_log(string $message)
{
    echo $message;
    echo PHP_EOL;
}

/**
 * 获取授权内容
 *
 * @param string $fileName
 * @param string $default
 * @return mixed
 */
function empowerFile(string $fileName, $default = '')
{
    $path = base_path("/{$fileName}.pem");
    if (!file_exists($path)) {
        return $default;
    }
    $content = file_get_contents($path);
    if (!$content) {
        return $default;
    }
    return $content;
}
