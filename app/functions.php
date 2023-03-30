<?php

use app\service\Upload;

/**
 * 验证器（支持场景验证）
 *
 * @param [type] $validate
 * @param array $data
 * @param string $scene
 * @return boolean
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
        throw new Exception((string)$class->getError());
    }
    return true;
}

/**
 * 读取配置项
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 * @param  string       $key
 * @param  integer      $cid
 * @return string|array
 */
function getHpConfig($key = '', $cid = 0): string|array
{
    $model = new \app\model\SystemConfig;
    $map = [];
    if ($cid) {
        $map['cid'] = $cid;
    }
    $data = [];
    if ($key) {
        $data = '';
        $map['name'] = $key;
        $info = $model->where($map)->find();
        if ($info) {
            if ($info['component'] == 'uploadify') {
                $files = explode(',', $info['value']);
                $list = [];
                foreach ($files as $k => $v) {
                    $list[$k] = Upload::url((string) $v);
                }
                $data = $list;
            } else {
                $data = $info['value'];
            }
        } else {
            $data = [];
        }
    } else {
        $list = $model
            ->where($map)
            ->order(['sort' => 'asc', 'id' => 'asc'])
            ->select();
        foreach ($list as $key => $value) {
            if ($value['component'] == 'uploadify') {
                $files = explode(',', $value['value']);
                $list = [];
                foreach ($files as $k => $v) {
                    $list[$k] = Upload::url((string) $v);
                }
                $data = $list;
            } else {
                // 其他选项
                $data[$value['name']] = $value['value'];
            }
        }
    }
    return $data;
}

/**
 * 友好时间显示
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 * @param  integer $time
 * @return void
 */
function friend_date(int $time)
{
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
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
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-12
 * @param  integer $len
 * @return integer
 */
function get_random(int $len = 6): int
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
 *
 * @return mixed|null
 */
function hp_admin_id()
{
    return session('hp_admin.id');
}

/**
 * 当前管理员
 *
 * @param null|array|string $fields
 * @return array|mixed|null
 */
function hp_admin($fields = null)
{
    if (!$admin = session('hp_admin')) {
        return null;
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
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc')
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
    $url = parse_url($uri);
    $params = http_build_query($data);
    if (isset($url['query'])) {
        $uri = "{$uri}&{$params}";
    } else {
        $uri = "{$uri}?{$params}";
    }
    return $uri;
}

/**
 * 字符串转真假
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-27
 * @param  string  $val
 * @param  boolean $return_null
 * @return boolean
 */
function is_true(string $val, bool $return_null = false): bool
{
    $boolval = (is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val);
    return ($boolval === null && !$return_null ? false : $boolval);
}

/**
 * 输出日志到终端（仅调试模式下有效）
 *
 * @Author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-11
 * @param  type   $str
 * @param  string $remarks
 * @return void
 */
function p($str, $remarks = '-----日志-----')
{
    if (config('app.debug')) {
        $currentDate = date('Y-m-d H:i:s');
        echo "时间：{$currentDate} {$remarks}";
        echo PHP_EOL;
        print_r($str);
        echo PHP_EOL;
    }
}
