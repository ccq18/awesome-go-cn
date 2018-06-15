<?php
require_once __DIR__ . '/vendor/autoload.php';
$conf = require __DIR__.'/config.php';
$s = file_get_contents('./SOURCE_README.md');
$arrs = explode("\n", $s);
$http = new \Util\Http();
//$i=0;
foreach ($arrs as $k => $line) {
    $ok = preg_match('/\s*\*\s+\[(.*?)\]\((.*?)\)(.*)/', $line, $match);
    if ($ok) {
        $rep = del_prefix($match[2], 'https://github.com/');
        //取得github star数据
        $repInfo = $http->getJson("https://{$conf['user']}:{$conf['pass']}@api.github.com/repos/" . $rep);
        $star = $repInfo['stargazers_count'] ?? '';
        if (!empty($star)) {
            $star = "star:{$star}";
        }
        $enTxt = ltrim($match[3], '- ');
        $txt = '';
        if (!empty($enTxt)) {
            $txt = str_translate_diver_google($enTxt);
            $txt = "** {$txt} **";
            $enTxt = "({$enTxt})";
        }
        $arrs[$k] = " * [{$match[1]}]({$match[2]}) {$star} $txt $enTxt";
        echo $arrs[$k] . PHP_EOL;
    }
}
$s = implode("\n", $arrs);
file_put_contents('./README.md', $s);
