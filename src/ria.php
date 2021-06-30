<?php
$html = file_get_contents("https://ria.ru/search/?query=%D1%84%D0%BB%D0%BE%D1%82+%D1%80%D1%84");

$listPattern = '|<div class="list-item__content">(.*)</div>|siU';
$urlPattern = '|href="([^"]+)"|';

$stopWords = '/(Sputnik|РИА)/';

$titlePattern = '|<h1 class="article__title">(.*)</h1>|';
$textPattern = '|<div class="article__text">(.*)</div>|siU';
$imagePattern = '|<div itemprop="image"[^>]+><a itemprop="url" href="([^"]+)"|';
$srcPattern = '|src="([^"]+)"|';

preg_match_all($listPattern, $html, $matches);
preg_match($urlPattern, $matches[1][0], $match);
//var_dump($match[1]);
$guid_arr = explode('/', $match[1]);
$slug = end($guid_arr);
$article = file_get_contents($match[1]);
preg_match_all($textPattern, $article, $txtArr);
//var_dump($txtArr[1]);
unset($txtArr[1][count($txtArr[1])-1]);

$str = '';
foreach($txtArr[1] as $par){
    preg_match($stopWords, $par, $res);
    if(empty($res)){
        $str .= trim(strip_tags($par))."\n\n";
    }
}

$dir = '../_posts/';
$fileName = $dir.date('Y-m-d').'-'.substr($slug, 0, -5).'.md';

preg_match($titlePattern, $article, $titleMatch);
preg_match($imagePattern, $article, $imageMatch);

$img_data = '';
$image1 = $imageMatch[1];

$Headers = @get_headers($image1);
if(preg_match("|200|", $Headers[0])) {
    $img_data = file_get_contents($image1);
}
$uri_arr = explode('.', $image1);
$ext = end($uri_arr);
$img = '<img alt="Новости флота"  src="data:image/'.$ext.';base64,'.base64_encode($img_data).'">';

foreach($txtArr[1] as $par){
preg_match($stopWords, $par, $res);
if(empty($res)){
$str .= trim(strip_tags($par))."\n";
}
}

$md = <<<EOD
---
layout: post
title: $titleMatch[1]
category: lenta
---

EOD;

$md .= $str."<!--more-->\n\n".$img;
    file_put_contents($fileName, $md);
?>
