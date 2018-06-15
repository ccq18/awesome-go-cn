<?php
function has_prefix($str, $prefix)
{
    return substr($str, 0, strlen($prefix)) == $prefix;
}

function format_md($s, $prefix = '# ')
{
    if (is_string($s)) {
        $arrs = explode("\n", $s);
    } else {
        $arrs = $s;
    }
    $rs = [];
    $now = [];
    $in = false;
    foreach ($arrs as $line) {
        if (has_prefix($line, $prefix)) {
            if (!empty($now)) {
                $rs[] = $now;
                $now = [];
            }
            $in = true;
            $now[] = $line;
        } elseif ($in) {
            $now[] = $line;

        } else {
            $rs[] = $line;
        }

    }
    if ($in) {
        foreach ($rs as $k => $v) {
            if (is_array($v) && count($v) > 1) {
                $rs[$k] = [$v[0], format_md(array_slice($v, 1), '#' . $prefix)];
            }
        }
    }
    return $rs;
}

function str_translate_diver_google($key)
{
    static $http = null;
    if (empty($http)) {
        $http = new Util\Http();
    }
    $rs =  $http->getJson('http://translate.google.cn/translate_a/single', [
        'q' => $key,
        'dt'=>'t',
        'client' => 'gtx',
        'sl' => 'en',
        'tl' => 'zh-CN'
    ]);
    return $rs[0][0][0]??'';
}

http://translate.google.cn/translate_a/single?client=gtx&amp;amp;amp;sl=en&amp;amp;amp;tl=zh-CN&amp;amp;amp;dt=t&amp;amp;amp;q=google

function str_translate_diver_youdao($key)
{
    static $http = null;
    if (empty($http)) {
        $http = new Util\Http();
    }

    return $http->getJson("http://fanyi.youdao.com/openapi.do", [
        'keyfrom' => 'codefanyi2',
        'key' => '394865828',
        'type' => 'data',
        'doctype' => 'json',
        'version' => '1.1',
        'q' => $key,
    ]);
}

function del_prefix($str, $prefix)
{
    if (has_prefix($str, $prefix)) {
        return substr($str, strlen($prefix));
    }
    return $str;
}