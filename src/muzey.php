<?php
//$feed = file_get_contents("https://mmflota.ru/index.php?option=com_content&view=category&id=8&format=feed&type=rss");

$feed = implode(file('https://mmflota.ru/index.php?option=com_content&view=category&id=8&format=feed&type=rss'));
$xml = simplexml_load_string($feed);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
//print_r($array);
$txtPattern = '|<div itemprop=\"articleBody\">(.*)</div>|Usi';
$exclude = '/(музе|Музей|онлайн|поздравляем)/Usi';
$date = '|\d{2}\.\d{2}\.\d{4}|Usi';
$remove = ['|См\..*\n|Usi'];
//$link = '/Источник изображения\:\s?(.*\.(jpg|png))/Usi';
$link = '/href="([^"]+)" (data-lightbox|data-title)/';
$md = '';
foreach($array['channel']['item'] as $item){
    $article = file_get_contents($item['link']);
    $guid_arr = explode('/', $item['guid']);
    $dir = '../_posts/';
    $fileName = '';
    $md = '---
layout: post
title: '.$item['title'].'
category: news
---';
    if(preg_match($txtPattern, $article, $match)){
        $html = preg_replace($remove, '',  $match[1]);
        $txt = trim(strip_tags($html));
        preg_match($date, $txt, $desc);
        $date_arr = array_reverse(explode('.' , $desc[0]));
        $dateF = implode('-', $date_arr);
        $fileName = $dir.$dateF.'-'.end($guid_arr).'.md';
        $txt = preg_replace($date, '',  $txt);

        preg_match_all($link, $match[1], $matches);
        preg_match($exclude, $txt, $stopList);
        $img_src = [];
        if(empty($stopList)){
            $links = array_unique($matches[1]);
            foreach($links as $url){
                    if(!in_array(trim($url), $img_src) && !strstr($url, 'watermarked')){
                        $img_src[] = trim($url);
                    }
            }
            $img = '';
            if(isset($img_src[0])){
                $ext = substr($img_src[0], -3);
                $img_data = '';
                $image1 = 'https://mmflota.ru'.$img_src[0];

$Headers = @get_headers($image1);
    if(preg_match("|200|", $Headers[0])) {
        $img_data = file_get_contents($image1);
    } else {
        $image2 = 'https://mmflota.ru'.$img_src[1];
        $Headers = @get_headers($image2);
        if(preg_match("|200|", $Headers[0])) {
            $img_data = file_get_contents($image2);
        }
    }
                //$img = '!['.$item['title'].'](data:image/'.$ext.';base64,'. base64_encode($img_data).')';
                $img = '<img alt="'.$item['title'].'"  src="data:image/jpeg;base64,'.base64_encode($img_data).'">';
            }

$txt_arr = explode("\n", $txt);
$txt_arr[1] = $txt_arr[1]."<!--more-->\n".$img;
$txt = implode("\n\n", $txt_arr);

$md .= '
'.$txt;
            file_put_contents($fileName, $md);

        }
    }
}

?>
