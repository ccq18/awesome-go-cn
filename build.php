<?php
require_once __DIR__ . '/vendor/autoload.php';

$s = file_get_contents('./SOURCE_README.md');
//$rs = FormatMd($s);
$arrs = explode("\n", $s);
$http = new \Util\Http();
//$i=0;
foreach ($arrs as $k => $line) {
    $ok = preg_match('/\s*\*\s+\[(.*?)\]\((.*?)\)(.*)/', $line, $match);
    if ($ok) {

        $rep = del_prefix($match[2], 'https://github.com/');

        $repInfo = $http->getJson('https://api.github.com/search/repositories', ['q' => $rep]);
        $star = $repInfo['items']['0']['stargazers_count'] ?? '';
        if (!empty($star)) {
            $star = "star:{$star}";
        }
        $txt = str_translate_diver_google($match[3]);
        $arrs[$k] = " * [{$match[1]}]({$match[2]}) {$star} $txt ({$match[3]})";

        echo $arrs[$k].PHP_EOL;

    }
}
$s = implode("\n",$arrs);
file_put_contents('./README.md',$s);
